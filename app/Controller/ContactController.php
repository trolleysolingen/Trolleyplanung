<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * Publishers Controller
 *
 * @property Publisher $Publisher
 * @property PaginatorComponent $Paginator
 */
class ContactController extends AppController {

	public $components = array('PublisherDAO', 'CongregationDAO');

	public function beforeFilter() {
		$publisher = $this->Session->read('publisher');
		if($publisher) {
			parent::checkLoginPermission();
			parent::checkActiveKillswitch();
		}
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$publisher = $this->Session->read('publisher');
		$this->set("publisher", $publisher);
		if($publisher) {
			$contactList = $this->PublisherDAO->getContactPersons($publisher);
			$this->set("contactList", $contactList);
			$this->Session->write('verwaltungTyp', $publisher['Congregation']['typ']);
		} else {
			// not logged in; decide from hostname if to display trolley or ffd
			// or check if parameter ffd is set
			$hostname = $_SERVER['HTTP_HOST'];
			if (substr( $hostname, 0, 3 ) === "ffd" || (array_key_exists('ffd', $this->params['url']) && $this->params['url']['ffd'] == "true")) {
				$this->Session->write('verwaltungTyp', 'FFD');
			} else {
				$this->Session->write('verwaltungTyp', 'Trolley');
			}			
		}

		if ($this->request->is('post')) {
			$useremail = $publisher["Publisher"]["email"];
			$username = $publisher["Publisher"]["prename"] . " " . $publisher["Publisher"]["surname"];
			$subject = $this->request->data["Contact"]["subject"];
			$message = $this->request->data["Contact"]["message"];

			$mail    = new CakeEmail();
			$result   = $mail->emailFormat('text')
				->from(array($useremail => $username))
				->to('info@trolley.jw-center.com')
				->subject($subject);

			if ($mail ->send($message)) {
				$this->Session->setFlash('Deine Nachricht wurde abgeschickt. Es wird sich so schnell es geht jemand darum kümmern.', 'default', array('class' => 'alert alert-success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Beim Verschicken deiner Nachricht ist ein Fehler aufgetreten. Bitte versuche es später noch einmal.', 'default', array('class' => 'alert alert-danger'));
			}
		} 

		$this->set('title_for_layout', 'Kontakt');
	}

}
