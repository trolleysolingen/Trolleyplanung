<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * Messages Controller
 *
 * @property Publisher $Publisher
 * @property PaginatorComponent $Paginator
 */
class TodosController extends AppController {

	public $components = array('Paginator', 'PublisherDAO', 'RequestHandler', 'CongregationDAO');

	public function beforeFilter() {
		parent::checkLoginPermission();
		parent::checkActiveKillswitch();
		$publisher = $this->Session->read('publisher');
		if (!$publisher) {
			return $this->redirect(array('controller' => 'start', 'action' => 'index'));
		} else if ($publisher['Role']['name'] != 'admin' && $publisher['Role']['name'] != 'congregation admin') {
			return $this->redirect(array('controller' => 'reservations', 'action' => 'index'));
		} else {
			$this->set("publisher", $publisher);
		}
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->set('title_for_layout', 'Todos');
		
		$this->Paginator->settings = array(
			'order' => array(
				'Todo.id' => 'asc',
			),
			'limit' => 1000000000
		);
		
		$this->Todo->recursive = 0;
		
		$openBugs = array();
		$closedBugs = array();
		$openFeatures = array();
		$finishedFeatures = array();
		
		foreach($this->Paginator->paginate('Todo') as $todo) {
			if($todo['Todotype']['name'] == 'Fehler' && $todo['Todo']['finishdate'] == "") {
				$openBugs[] = $todo;
			} else if($todo['Todotype']['name'] == 'Funktion' && $todo['Todo']['finishdate'] == "") {
				$openFeatures[] = $todo;
			} else if($todo['Todotype']['name'] == 'Fehler' && $todo['Todo']['finishdate'] != "") {
				$closedBugs[] = $todo;
			} else {
				$finishedFeatures[] = $todo;
			}
		}
		$this->set("openBugs", $openBugs);
		$this->set("openFeatures", $openFeatures);
		$this->set("closedBugs", $closedBugs);
		$this->set("finishedFeatures", $finishedFeatures);
		
	}
	
	/**
	 * add method
	 *
	 * @return void
	 */
	public function add() {
		$publisher = $this->Session->read('publisher');

		if ($this->request->is('post')) {
			$this->Todo->create();
			$newTodo = $this->request->data;
			if(isset($this->request->data["Todo"]["todotype_id"])) {
				$newTodo['Todo']['creationdate'] = date("Y-m-d");
				$newTodo['Todo']['reporter_id'] = $publisher['Publisher']['id'];
				
				if ($this->Todo->save($newTodo)) {
					$this->Session->setFlash('Deine Anfrage wurde gespeichert. Vielen Dank für dein Feedback.', 'default', array('class' => 'alert alert-success'));
					
					$post_id=$this->Todo->getLastInsertId();
					$options = array('conditions' => array('Todo.' . $this->Todo->primaryKey => $post_id));
					$todo = $this->Todo->find('first', $options);
					
					$subject = "Neues Todo " . $todo['Todotype']['name'] . " #" . $todo['Todo']['id'] . ": " . $todo['Todo']['shortdesc'];
					$text = $todo['Todo']['description'];
						
					$username = $todo["Reporter"]["prename"] . " " . $todo["Reporter"]["surname"];
					$mail    = new CakeEmail();
					$result   = $mail->emailFormat('text')
						->from(array($todo['Reporter']['email'] => $username))
						->to('info@trolley.jw-center.com')
						->subject($subject);

					$mail ->send($text);
					
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash('Deine Anfrage konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
				}
			} else {
				$this->Session->setFlash('Bitte gib einen Typ an.', 'default', array('class' => 'alert alert-danger'));
			}
		}
	}
	
	public function view($id = null) {
		if (!$this->Todo->exists($id)) {
			throw new NotFoundException(__('Ungültiges Todo'));
		}
		$options = array('conditions' => array('Todo.' . $this->Todo->primaryKey => $id));
		$this->set('todo', $this->Todo->find('first', $options));
	}
	
	public function edit($id = null) {
		$publisher = $this->Session->read('publisher');

		if (!$this->Todo->exists($id)) {
			throw new NotFoundException(__('Ungültiges Todo'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Todo->save($this->request->data)) {
				$this->Session->setFlash('Deine Anfrage wurde gespeichert. Vielen Dank für dein Feedback.', 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Deine Anfrage konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Todo.' . $this->Todo->primaryKey => $id));
			$this->request->data = $this->Todo->find('first', $options);
		}
		
		$this->set('publisher', $publisher);
	}
	
	public function startWork($id = null) {
		$publisher = $this->Session->read('publisher');

		if (!$this->Todo->exists($id)) {
			throw new NotFoundException(__('Ungültiges Todo'));
		}
		
		$options = array('conditions' => array('Todo.' . $this->Todo->primaryKey => $id));
		$todo = $this->Todo->find('first', $options);
		
		$subject = "Arbeitsbeginn " . $todo['Todotype']['name'] . " #" . $todo['Todo']['id'] . ": " . $todo['Todo']['shortdesc'];
		$text = "Liebe(r) " . $todo['Reporter']['prename'] . " " . $todo['Reporter']['surname'] . ",\n"
			. "\n"
			. "Ab sofort wird an deinem gemeldeten Todo #" . $todo['Todo']['id'] . ": \"" . $todo['Todo']['shortdesc'] . "\" gearbeitet.\n"
			. "\n"
			. "Du bekommst eine Benachrichtigung sobald der Vogang abgeschlossen wurde. \n"
			. "\n"
			. "Viele Grüße \n"
			. "Deine Trolleyverwaltung \n";
		
		$todo['Todo']['worker_id'] = $publisher['Publisher']['id'];
		$todo['Todo']['startdate'] = date("Y-m-d");
		
		if ($this->Todo->save($todo)) {
			$success = $this->sendMail($todo['Reporter']['email'], $subject, $text);
			if($success){
				$this->Session->setFlash('Du bist nun der Bearbeiter des Vorgangs. Der Reporter wurde auch darüber benachrichtigt.', 'default', array('class' => 'alert alert-success'));
			} else {
				$this->Session->setFlash('Du bist nun der Bearbeiter des Vorgangs. Der Reporter konnte aber leider nicht benachrichtigt werden.', 'default', array('class' => 'alert alert-warning'));
			}
			return $this->redirect(array('action' => 'index'));
		} else {
			$this->Session->setFlash('Deine Anfrage konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
		}
	}

public function endWork($id = null, $standard) {
		$publisher = $this->Session->read('publisher');

		if (!$this->Todo->exists($id)) {
			throw new NotFoundException(__('Ungültiges Todo'));
		}
		
		$options = array('conditions' => array('Todo.' . $this->Todo->primaryKey => $id));
		$todo = $this->Todo->find('first', $options);
		
		$todo['Todo']['finishdate'] = date("Y-m-d");
		
		if($standard) {
			$subject = "Fertigstellung " . $todo['Todotype']['name'] . " #" . $todo['Todo']['id'] . ": " . $todo['Todo']['shortdesc'];
			$text = "Liebe(r) " . $todo['Reporter']['prename'] . " " . $todo['Reporter']['surname'] . ",\n"
				. "\n"
				. "Gerade wurde dein gemeldetes Todo #" . $todo['Todo']['id'] . ": \"" . $todo['Todo']['shortdesc'] . "\" fertiggestellt.\n"
				. "\n"
				. "Solltest du Fragen dazu haben, schicke uns eine Mail. \n"
				. "\n"
				. "Viele Grüße \n"
				. "Deine Trolleyverwaltung \n";
				
			if ($this->Todo->save($todo)) {
				$success = $this->sendMail($todo['Reporter']['email'], $subject, $text);
				if($success){
					$this->Session->setFlash('Der Vorgang wurde nun fertiggestellt. Der Reporter wurde auch darüber benachrichtigt.', 'default', array('class' => 'alert alert-success'));
				} else {
					$this->Session->setFlash('Der Vorgang wurde nun fertiggestellt. Der Reporter konnte aber leider nicht benachrichtigt werden.', 'default', array('class' => 'alert alert-warning'));
				}
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Deine Anfrage konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			if ($this->Todo->save($todo)) {
				$this->Session->setFlash('Der Vorgang wurde nun fertiggestellt. Bitte schicke nun noch eine Erklärungsmail dazu.', 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('controller' => 'messages', 'action' => 'index'));
			} else {
				$this->Session->setFlash('Deine Anfrage konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
			}
		}
	}	
}
