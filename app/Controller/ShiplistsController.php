<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * Messages Controller
 *
 * @property Publisher $Publisher
 * @property PaginatorComponent $Paginator
 */
class ShiplistsController extends AppController {

	public $components = array('PublisherDAO', 'CongregationDAO', 'ShiplistDAO');
	public $uses = array('Publisher', 'Shiplist');

	public function beforeFilter() {
		parent::checkLoginPermission();
		parent::checkActiveKillswitch();
		parent::checkDataprotection();
		$publisher = $this->Session->read('publisher');
		if (!$publisher) {
			return $this->redirect(array('controller' => 'start', 'action' => 'index'));
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
		$this->set('title_for_layout', 'Schiffslisten');
		
		$shiplist = null;
		if ($this->request->is('post')) {
			$shiplist = $this->ShiplistDAO->searchShiplists($this->request->data['Shiplist']["shipname"], $this->request->data['Shiplist']["imo"]);		
		}
		$this->set("shiplist", $shiplist);
	}
	
}
