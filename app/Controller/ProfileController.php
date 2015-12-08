<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * Profile Controller
 *
 * @property Publisher $Publisher
 * @property PaginatorComponent $Paginator
 */
class ProfileController extends AppController {

	public $components = array('PublisherDAO', 'CongregationDAO');
	public $uses = array('Publisher');

	public function beforeFilter() {
		parent::checkLoginPermission();
		parent::checkActiveKillswitch();
		$publisher = $this->Session->read('publisher');
		if (!$publisher) {
			if (!$this->request->is('ajax')) {
				return $this->redirect(array('controller' => 'start', 'action' => 'index'));
			}
		}
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$publisher = $this->Session->read('publisher');
		
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Publisher->save($this->request->data)) {
				$this->Session->setFlash('Deine Änderungen wurden gespeichert.', 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Deine Änderungen konnten nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Publisher.' . $this->Publisher->primaryKey => $publisher['Publisher']['id']));
			$this->request->data = $this->Publisher->find('first', $options);
		}
		
		$this->set("publisher", $publisher);
		$this->set('title_for_layout', 'Profil');
	}
	
	public function setNewSetting($state = null, $module) {
		
		$publisher = $this->Session->read('publisher');
		$publisher['Publisher'][$module] = $state;
		
		if ($this->Publisher->save($publisher)) {
			$this->Session->setFlash('Deine Änderung wurde gespeichert', 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash('Deine Änderung konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
		}
		
		$publisher = $this->Session->read('publisher');
		$publisher2 = $this->PublisherDAO->getById($publisher);
		$this->Session->write('publisher', $publisher2);
		
		return $this->redirect(array('action' => 'index'));
	}
}
