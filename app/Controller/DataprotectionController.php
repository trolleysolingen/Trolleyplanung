<?php
App::uses('AppController', 'Controller');
/**
 * Help Controller
 *
 */
class DataprotectionController extends AppController {

	public $components = array('PublisherDAO');
	var $uses = array('Publisher');

	public function beforeFilter() {
		
		$publisher = $this->Session->read('publisher');
		if (!$publisher) {
			return $this->redirect(array('controller' => 'start', 'action' => 'index'));
		}
		$this->set("publisher", $publisher);
	}
	/**
	 * index method
	 *
	 * @return void
	 */
	public function index() {
		$this->set('title_for_layout', 'Einwilligung zur Datenverarbeitung');
	}

	
	public function reject() {
		$publisher = $this->Session->read('publisher');
		
		$this->Publisher->delete($publisher['Publisher']['id']);		
		$this->Session->delete('publisher');
		
		$this->redirect(array('controller' => 'start', 'action' => 'index', '?' => array('ffd' => $this->Session->read('verwaltungTyp') == 'FFD' ? 'true' : 'false')));
	}
	
	public function accept() {
		$publisher = $this->Session->read('publisher');
		
		$publisher['Publisher']['dataprotection'] = 1;
		$publisher['Publisher']['dataprotection_date'] = date('Y-m-d H:i:s');
		
		$this->Publisher->save($publisher);
		$this->Session->write('publisher', $publisher);
		
		$this->redirect(array('controller' => 'reservations', 'action' => 'index'));
	}
}