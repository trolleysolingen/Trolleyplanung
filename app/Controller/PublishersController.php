<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * Publishers Controller
 *
 * @property Publisher $Publisher
 * @property PaginatorComponent $Paginator
 */
class PublishersController extends AppController {

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Paginator', 'PublisherDAO', 'RequestHandler', 'ReservationDAO');
	public $uses = array('Publisher', 'Reservation');

	public function beforeFilter() {
		$publisher = $this->Session->read('publisher');
		if (!$publisher) {
			return $this->redirect(array('controller' => 'start', 'action' => 'index'));
		} else if ($publisher['Role']['name'] != 'admin' && $publisher['Role']['name'] != 'congregation admin') {
			return $this->redirect(array('controller' => 'reservations', 'action' => 'index'));
		}
	}

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index() {
		$publisher = $this->Session->read('publisher');

		$this->Paginator->settings = array(
			'order' => array(
				'Publisher.surname' => 'asc',
				'Publisher.prename' => 'asc',
			)
		);

		$this->Publisher->recursive = 0;
		$this->set('publishers',
			$this->Paginator->paginate('Publisher', array('Publisher.congregation_id' => $publisher['Congregation']['id'])));
		$this->set('publisher', $publisher);

		$this->set('title_for_layout', 'Verkündiger');
	}

	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function view($id = null) {
		if (!$this->Publisher->exists($id)) {
			throw new NotFoundException(__('Invalid publisher'));
		}
		$options = array('conditions' => array('Publisher.' . $this->Publisher->primaryKey => $id));
		$this->set('publisher', $this->Publisher->find('first', $options));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add() {
		$publisher = $this->Session->read('publisher');

		if ($this->request->is('post')) {
			$this->Publisher->create();
			if ($this->Publisher->save($this->request->data)) {
				$this->Session->setFlash('Der Verkündiger wurde gespeichert.', 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Der Verkündiger konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
			}
		}
		$roles = $this->Publisher->Role->find('list', array('fields' => array('id', 'description'), 'conditions' => array('name not in' => array('admin', 'guest'))));
		$this->set(compact('roles'));
		$this->set('publisher', $publisher);
	}

	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function edit($id = null) {
		$publisher = $this->Session->read('publisher');

		if (!$this->Publisher->exists($id)) {
			throw new NotFoundException(__('Ungültiger Verkündiger'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Publisher->save($this->request->data)) {
				$this->Session->setFlash('Der Verkündiger wurde gespeichert.', 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Der Verkündiger konnte nicht gelöscht werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Publisher.' . $this->Publisher->primaryKey => $id));
			$this->request->data = $this->Publisher->find('first', $options);

			if ($this->request->data['Publisher']['congregation_id'] != $publisher['Publisher']['congregation_id']) {
				return $this->redirect(array('controller' => 'publishers', 'action' => 'index'));
			}
		}

		$roles = $this->Publisher->Role->find('list', array('fields' => array('id', 'description'), 'conditions' => array('name not in' => array('guest',
			($this->request->data && $this->request->data['Role']['name'] == 'admin' ? '' : 'admin')))));

		$this->set(compact('roles'));
		$this->set('publisher', $publisher);
	}

	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function delete($id = null) {
		$publisher = $this->Session->read('publisher');

		$this->Publisher->id = $id;

		$options = array('conditions' => array('Publisher.' . $this->Publisher->primaryKey => $id));
		$publisherToDelete = $this->Publisher->find('first', $options);

		if ($publisherToDelete['Publisher']['congregation_id'] != $publisher['Publisher']['congregation_id']) {
			return $this->redirect(array('controller' => 'publishers', 'action' => 'index'));
		}

		if (!$this->Publisher->exists()) {
			throw new NotFoundException(__('Ungültiger Verkündiger'));
		}

		// delete reservations of publisher
		$reservations = $this->Reservation->find('all', array('conditions' => array('OR' =>  array('publisher1_id' => $id, 'publisher2_id' => $id))));
		foreach ($reservations as $reservation) {
			//debug($reservation);

			$this->ReservationDAO->deletePublisher(
				$publisherToDelete['Congregation']['id'],
				$reservation['Reservation']['day'],
				$reservation['Reservation']['timeslot_id'],
				$publisherToDelete,
				false);
		}

		if ($this->Publisher->delete()) {
			$this->Session->setFlash('Der Verkündiger wurde gelöscht.', 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash('Der Verkündiger konnte nicht gelöscht werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}


	public function autocomplete() {
		$publisher = $this->Session->read('publisher');
		$matchingPublishers = $this->PublisherDAO->getByAutocomplete($this->request->query('query'), $publisher);

		$publishersJson = array();
		foreach ($matchingPublishers as $matchingPublisher) {
			$publishersJson[] = array(
				'id' => $matchingPublisher['Publisher']['id'],
				'name' => $matchingPublisher['Publisher']['prename'] . ' ' . $matchingPublisher['Publisher']['surname']);
		}
		$this->set("publishers", $publishersJson);

		$this->set("_serialize", array("publishers"));
	}

	public function sendAccount($id) {
		$publisher = $this->Session->read('publisher');

		if (!$this->Publisher->exists($id)) {
			throw new NotFoundException(__('Ungültiger Verkündiger'));
		}
		$options = array('conditions' => array('Publisher.' . $this->Publisher->primaryKey => $id));
		$publisherToSendAccount = $this->Publisher->find('first', $options);

		$subject = "Zugangsdaten zur Trolleyverwaltung";
		$message = "Liebe(r) " . $publisherToSendAccount["Publisher"]["prename"] . " " . $publisherToSendAccount["Publisher"]["surname"] . ",\n"
			. "\n"
			. "anbei findest Du Deine Zugangsdaten zur Trolleyverwaltung Deiner Versammlung.\n"
			. "Bitte bewahre diese Zugangsdaten gut auf.\n"
			. "\n"
			. "http://trolley.jw-center.com \n"
			. "Benutzername: " . $publisherToSendAccount["Publisher"]["email"] . "\n"
			. "Passwort: " . $publisherToSendAccount["Publisher"]["password"] . "\n"
			. "\n"
			. "Bei Fragen und Problemen wende Dich bitte an: " . $publisher['Publisher']['email'] . "\n"
			. "Oder nutze alternativ die Kontakt Seite: \n"
			. "http://trolley.jw-center.com/contact \n\n"
			. "Viele Grüße \n"
			. "Deine Trolleyverwaltung \n";
			
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		if (strpos($actual_link,'trolleydemo') === false) {
			if (strpos($publisherToSendAccount["Publisher"]["email"], "@demo.de") === false) {
				$mail = new CakeEmail('smtp');
				$result = $mail->emailFormat('text')
					->to($publisherToSendAccount["Publisher"]["email"])
					->subject($subject);
				
				if ($mail->send($message)) {
					$this->Session->setFlash('Die Zugangsdaten wurden verschickt.', 'default', array('class' => 'alert alert-success'));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash('Beim Verschicken der Zugangsdaten ist ein Fehler aufgetreten. Bitte versuche es später noch einmal.', 'default', array('class' => 'alert alert-danger'));
				}
			}
		} else {
			$this->redirect(array('action' => 'index'));
		}
	}
}