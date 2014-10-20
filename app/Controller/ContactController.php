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

	public $components = array('PublisherDAO');

	public function beforeFilter() {
	
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
	}

}
