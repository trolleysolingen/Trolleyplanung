<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * Messages Controller
 *
 * @property Publisher $Publisher
 * @property PaginatorComponent $Paginator
 */
class MessagesController extends AppController {

	public $components = array('PublisherDAO', 'CongregationDAO');

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
		$this->set('title_for_layout', 'Neue Nachricht');
		
		if ($this->request->is('post')) {
			if(isset($this->request->data["Message"]["group"])) {
				$this->Message->create();
				$newMessage = $this->request->data;
				
				$publisher = $this->Session->read('publisher');
				if($this->request->data["Message"]["group"] == "myPub") {
					$mailList = $this->PublisherDAO->getAllCongMailAdresses($publisher);
					$newMessage['Message']['congregation_id'] = $publisher['Publisher']['congregation_id'];
				} else if($this->request->data["Message"]["group"] == "myCongAd") {
					$mailList = $this->PublisherDAO->getAllCongAdminMailAdresses($publisher);
					$newMessage['Message']['congregation_id'] = $publisher['Publisher']['congregation_id'];
					$newMessage['Message']['role_id'] = 4;
				} else if($this->request->data["Message"]["group"] == "allUsers") {		
					$mailList = $this->PublisherDAO->getAllMailAdresses();
				} else if($this->request->data["Message"]["group"] == "allCongAd") {
					$mailList = $this->PublisherDAO->getAllAdminMailAdresses();
					$newMessage['Message']['role_id'] = 4;
				}
				
				if ($this->Message->save($newMessage)) {
				
					$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
					if (strpos($actual_link,'trolleydemo') === false) {
						$error = 0;
						$emailArray = [];
						foreach ($mailList as $publisherToSendAccount) {
							$emailArray[] = $publisherToSendAccount["Publisher"]["email"];
						}
						$success = $this->sendMailBcc($emailArray, $this->request->data["Message"]["subject"], $this->request->data["Message"]["text"]);

						
						if(!$success) {
							$this->Session->setFlash('Die Nahrichten konnten nicht verschickt werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
						} else {
							$this->Session->setFlash('Die Nachrichten wurden verschickt.', 'default', array('class' => 'alert alert-success'));
						}
						
						$this->redirect(array('controller' => 'publishers', 'action' => 'index'));
					} else {
						$this->redirect(array('controller' => 'publishers', 'action' => 'index'));
					}
				} else {
					$this->Session->setFlash('Die Nachricht konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
				}
			} else {
				$this->Session->setFlash('Bitte wähle die Empfänger deiner Nachricht aus', 'default', array('class' => 'alert alert-danger'));
			}
		}
	}
}
