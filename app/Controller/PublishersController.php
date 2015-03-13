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
	public $components = array('Paginator', 'PublisherDAO', 'RequestHandler', 'ReservationDAO', 'CongregationDAO');
	public $uses = array('Publisher', 'Reservation');

	public function beforeFilter() {
		parent::checkLoginPermission();
		parent::checkActiveKillswitch();
		$publisher = $this->Session->read('publisher');
		if (!$publisher) {
			return $this->redirect(array('controller' => 'start', 'action' => 'index'));
		} else if ($publisher['Role']['name'] != 'admin' && $publisher['Role']['name'] != 'congregation admin' && $this->action != "index") {
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
			$newPublisher = $this->request->data;
			$newPublisher['Publisher']['password'] = $this->randomPassword();
			
			if ($this->Publisher->save($newPublisher)) {
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
	
	public function randomPassword() {
		$alphabet = "abcdefghijklmnopqrstuwxyz0123456789";
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 5; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
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
				$this->Session->setFlash('Der Verkündiger konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
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
	
	public function sendMails($publisherToSendAccount) {

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
			. "Unter dem Menüpunkt \"Schichten\", kannst du dich und einen Partner für eine Schicht eintragen, oder dich einfach eintragen und abwarten wer sich noch dazu einträgt. Sobald sich jemand zu deiner Schicht einträgt oder löscht, bekommst du eine Benachrichtigung per E-Mail. \n"
			. "\n"
			. "Ganz nach dem Motto aus Römer 12:10 \"Habt in brüderlicher Liebe innige Zuneigung zueinander. In Ehrerbietung komme einer dem anderen zuvor.\", denkt bitte daran, dass natürlich der Grundsatz gilt: Wer zuerst kommt, mahlt zuerst. ABER wir möchten doch darauf achten, dass wir uns nicht überall eintragen nur um für uns etwas reserviert zu haben und zu schauen wo sich jemand dazu einträgt und unseren Schwestern und Brüdern die Schichten damit wegzunehmen. \n"
			. "\n"
			. "Wir sind uns sicher, dass Jehova deine Bemühungen am öffentlichen Zeugnisgeben teil zu nehmen, segnen wird. \n"
			. "\n"
			. "Für Fragen, aufgefallene Fehler, Verbesserungsvorschläge und Anregungen nutze bitte die Kontakt Seite: \n"
			. "http://trolley.jw-center.com/contact \n"
			. "\n"
			. "Viele Grüße \n"
			. "Deine Trolleyverwaltung \n"; 
			
		$subject2 = "Informationen zum Adminbereich";
		$message2 = "Liebe(r) " . $publisherToSendAccount["Publisher"]["prename"] . " " . $publisherToSendAccount["Publisher"]["surname"] . ",\n"
			. "\n"
			. "Du wurdest als Versammlungsadmin deiner Versammlung angelegt. Wir freuen uns, dass ihr als Versammlung am öffentlichen Zeugnisgeben teilnehmt und auch, dass ihr euch dazu entschlossen habt zur Verwaltung unser Tool zu benutzen. \n"
			. "Deine Zugangsdaten müsstest du schon in einer gesonderten Mail zugesandt bekommen haben. Anbei aber noch ein paar spezielle Dinge, die den Adminbereich betreffen: \n"
			. "\n"
			. "Hinter dem Menüpunkt \"Einstellungen\", kannst du deinen Versammlungsnamen ändern und die Tage einstellen, für die in eurem Versammlungsgebiet Trolleyschichten eingetragen werden können. Außerdem können hier auch verschiedene Module zu deiner Versammlung aktiviert werden. Die jeweiligen Informationen dazu kannst du auf dem jeweiligen Info Button links neben dem Modul einsehen.\n"
			. "\n"
			. "Unter dem Menüpunkt \"Verkündiger\" kannst die Verkündiger deiner Versammlung verwalten. Du kannst entweder Verkündiger oder Admins, die die gleichen Berechtigungen wie du haben, anlegen. Bei der Verkündigeranlage wäre es vorteilhaft, wenn du eine Telefonnummer des Verkündigers mit angibst. Diese wird in in der Schichtplanung zu jedem Verkündiger angezeigt. So können die Verkündiger untereinander leichter Kontakt herstellen. Soll ein Verkündiger Trolleydienst machen dürfen, aber dies nur als Partner mit einem etwas erfahrenerem Verkündiger, gib einfach keine E-mail Adresse zu diesem Verkündiger an. So hat er keinen Login zum einloggen, steht aber in eurer Verkündiger Liste. Später gehe ich darauf ein, was das für einen Sinn macht. Die Passwörter für die Verkündiger werden automatisch generiert. Mit einem Klick auf den Button \"Alle Zugangsdaten verschicken\", versendest du eine Mail an jeden Verkündiger mit einer Email-Adresse in deiner Verkündigerliste.\n Alternativ kannst du auch auf das Brief Symbol neben dem Verkündiger in der Liste klicken. Dann werden die Zugangsdaten nur für diesen Verkündiger an seine Mail-Adresse verschickt.\n"
			. "\n"
			. "Unter dem Menüpunkt \"Schichtzeiten\", kannst du die Schichtzeiten, deiner Versammlung hinzufügen, ändern und löschen. Bitte achte darauf, dass du keine Schichtzeiten löschen kannst, zu denen sich Verkündiger schon eingetragen haben. Falls du also deinen laufenden Schichtplan ändern willst, musst du einfach nur die Schichtzeiten ändern. Falls du dennoch Schichten löschen musst, schreib uns eine Mail und wir werden uns dann um eine Lösung des Problems bemühen. \n"
			. "\n"
			. "Hinter dem Menüpunkt \"Schichten\" verbirgt sich die Schichtverwaltung. Hier können sich Verkündiger und auch du für Schichten, maximal 12 Wochen im voraus eintragen. Wenn sich ein Verkündiger einträgt, hat er die Möglichkeit noch einen Partner zu sich in die Schicht einzutragen. Es öffnet sich ein Fenster, in dem er einen Namen eingeben kann, sobald er anfängt zu tippen, öffnen sich Verkündiger Vorschläge anhand seiner eingegebenen Buchstaben. Hier tauchen dann auch die Verkündiger ohne Login auf. Wenn ein Verkündiger einen Partner einträgt, der ihm nicht vorgeschlagen wird von der Suche (weil er nicht in eurer Verkündiger Liste steht und z.B. aus einer anderen Versammlung kommt) bekommen alle Versammlungsadmins eine Info Mail um zu überprüfen ob der Partner für den Trolleydienst geeignet ist. Wenn sich Verkündiger von einer Schicht löschen möchten, wird er gefragt ob er auch den Partner aus seiner Schicht mitlöschen möchte. So müssen sich nicht beide einloggen und löschen, wenn sie ihren Dienst absagen.  \n"
			. "\n"
			. "Natürlich untersteht dieses Tool der ständigen Weiterentwicklung. Wir haben noch einige Ideen und Features, die wir in weiteren Versionen umsetzen wollen. Wenn ihr Fragen habt, nutzt bitte das Kontaktformular oder die e-mail Adresse unter: \n"
			. "http://trolley.jw-center.com/contact \n"
			. "\n"
			. "Für alle weiteren Verbesserungsvorschläge und Fehler gibt es den Menüpunkt \"Todos\". Dort könnt ihr am einfachsten Fehler melden und auch Funktionen fordern. Gleichzeitig seht ihr auch den jeweiligen Bearbeitungsstand alles offenen Todos. \n"
			. "\n"
			. "Wir danken euch für eure Mithilfe und Unterstützung und wünschen euch Jehovas Segen für euren Trolleydienst. \n"
			. "\n"
			. "Viele Grüße \n"
			. "Deine Trolleyverwaltung \n"; 
		
		$success = $this->sendMail($publisherToSendAccount["Publisher"]["email"], $subject, $message);
		
		if($success) {
			if($publisherToSendAccount['Role']['name'] == 'congregation admin') {
				$success = $this->sendMail($publisherToSendAccount["Publisher"]["email"], $subject2, $message2);
			}
		}
		
		return $success;
	}

	public function sendAccount($id) {
		$publisher = $this->Session->read('publisher');

		if (!$this->Publisher->exists($id)) {
			throw new NotFoundException(__('Ungültiger Verkündiger'));
		}
		
		$options = array('conditions' => array('Publisher.' . $this->Publisher->primaryKey => $id));
		$publisherToSendAccount = $this->Publisher->find('first', $options);
		
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		if (strpos($actual_link,'trolleydemo') === false) {
			if (strpos($publisherToSendAccount["Publisher"]["email"], "@demo.de") === false) {
				$success = $this->sendMails($publisherToSendAccount);
				if(!$success) {
					$this->Session->setFlash('Die Zugangsdaten konnten nicht verschickt werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
				} else {
					$this->Session->setFlash('Die Zugangsdaten wurden verschickt.', 'default', array('class' => 'alert alert-success'));
				}
			}
			$this->redirect(array('action' => 'index'));
		} else {
			$this->redirect(array('action' => 'index'));
		}
	}
	
	public function sendMultiAccounts() {
		$publisher = $this->Session->read('publisher');
		
		$mailList = $this->PublisherDAO->getAllCongMailAdresses($publisher);
		
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		if (strpos($actual_link,'trolleydemo') === false) {
			$error = 0;
			foreach ($mailList as $publisherToSendAccount) {
				$success = $this->sendMails($publisherToSendAccount);
				
				if(!$success) {
					$error++;
				}
			}
			
			if($error>0) {
				$this->Session->setFlash('Die Zugangsdaten konnten nicht verschickt werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
			} else {
				$this->Session->setFlash('Die Zugangsdaten wurden verschickt.', 'default', array('class' => 'alert alert-success'));
			}
			
			$this->redirect(array('action' => 'index'));
		} else {
			$this->redirect(array('action' => 'index'));
		}
	}
	
	public function switchKey($id = null) {
		$options = array('conditions' => array('Publisher.' . $this->Publisher->primaryKey => $id));
		$dbPublisher = $this->Publisher->find('first', $options);
		
		if($dbPublisher['Publisher']['kdhall_key']) {
			$dbPublisher['Publisher']['kdhall_key'] = 0;
		} else {
			$dbPublisher['Publisher']['kdhall_key'] = 1;
		}
		
		if ($this->Publisher->save($dbPublisher)) {
			$this->Session->setFlash('Deine Änderung wurde gespeichert', 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash('Deine Änderung konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
		}
		
		$publisher = $this->Session->read('publisher');
		$publisher2 = $this->PublisherDAO->getById($publisher);
		$this->Session->write('publisher', $publisher2);
		
		$this->redirect(array('action' => 'index'));
	}
}