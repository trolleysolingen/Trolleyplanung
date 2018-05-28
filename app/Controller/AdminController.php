<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * Profile Controller
 *
 * @property Publisher $Publisher
 * @property PaginatorComponent $Paginator
 */
class AdminController extends AppController {

	public $components = array('PublisherDAO', 'CongregationDAO');
	public $uses = array('Publisher', 'Congregation');

	public function beforeFilter() {
		parent::checkLoginPermission();
		parent::checkActiveKillswitch();
		$publisher = $this->Session->read('publisher');
		if (!$publisher) {
			if (!$this->request->is('ajax')) {
				return $this->redirect(array('controller' => 'start', 'action' => 'index'));
			}
		} else if ($publisher['Role']['name'] != 'admin') {
			return $this->redirect(array('controller' => 'reservations', 'action' => 'index'));
		}
		
		$this->set("publisher", $publisher);
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$publisher = $this->Session->read('publisher');
		$congregations = $this->Congregation->find('all', array('fields' => array('Congregation.id', 'Congregation.name'),'recursive' => -1));
		$this->set("congregations", $congregations);
		
		$allUserCount = $this->PublisherDAO->getAllUserCount();
		$allDataprotectionUserCount = $this->PublisherDAO->getAllDataprotectionUserCount();
		$this->set("allUserCount", $allUserCount);
		$this->set("allDataprotectionUserCount", $allDataprotectionUserCount);
		
		if ($this->request->is('post')) {
			$options = array('conditions' => array('Congregation.' . $this->Congregation->primaryKey => $this->request->data["Congregation"]["kill"]));
			$congregation = $this->Congregation->find('first', $options);
			
			$congregation['Congregation']['killswitch'] = 1;
			
			if ($this->Congregation->save($congregation)) {
				$this->Session->setFlash('Die Versammlung' . $congregation['Congregation']['name'] . ' wurde für Logins deaktiviert.', 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('controller' => 'admin', 'action' => 'index'));
			} else {
				$this->Session->setFlash('Die Versammlung konnte nicht deaktiviert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
			}
		}
	}
	
	public function logoutAllUsers() {
		$this->PublisherDAO->logoutAllUsers();
		$this->Session->setFlash('Alle Verkündiger wurden ausgeloggt!', 'default', array('class' => 'alert alert-success'));
		return $this->redirect(array('controller' => 'admin', 'action' => 'index'));
	}
	
	public function killswitchAllCongregations() {
		$this->CongregationDAO->killswitchAllCongregations();
		$this->Session->setFlash('Alle Versammlungen wurden deaktiviert!', 'default', array('class' => 'alert alert-success'));
		$this->logoutAllUsers();
	}
	
	public function removeKillswitchFromAllCongregations() {
		$this->CongregationDAO->removeKillswitchFromAllCongregations();
		$this->Session->setFlash('Alle Versammlungen wurden wieder aktiviert!', 'default', array('class' => 'alert alert-success'));
		return $this->redirect(array('controller' => 'admin', 'action' => 'index'));
	}
	
	public function searchpublisher() {
		$publisherSearchList = $this->PublisherDAO->searchPublisher($this->request->data["Publisher"]["searchstring"]);
		$this->set("publisherSearchList", $publisherSearchList);
	}
	
	public function killswitchOneCongregation() {
	
	}
}
