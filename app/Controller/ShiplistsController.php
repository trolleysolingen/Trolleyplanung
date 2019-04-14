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
	public $uses = array('Shiplist', 'Publisher');

	public function beforeFilter() {
		parent::checkLoginPermission();
		parent::checkActiveKillswitch();
		parent::checkDataprotection();
		$publisher = $this->Session->read('publisher');
		
		if (!$publisher) {
			return $this->redirect(array('controller' => 'start', 'action' => 'index'));
		} else if ($this->action == 'export' && $publisher['Role']['name'] != 'admin' && $publisher['Role']['name'] != 'congregation admin') {
			return $this->redirect(array('controller' => 'shiplists', 'action' => 'index'));			
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
		$publisher = $this->Session->read('publisher');
		
		$this->set('title_for_layout', 'Schiffslisten');
		
		$shiplist = null;
		if ($this->request->is('post')) {
			$shiplist = $this->ShiplistDAO->searchShiplists($this->request->data['Shiplist']["shipname"], $this->request->data['Shiplist']["imo"]);		
		}
		$this->set("shiplist", $shiplist);
		$this->set("publisher", $publisher);
	}
	
	public function export() {
		
		$this->response->download("export.csv");

		$data = $this->Shiplist->find('all');
		$this->set(compact('data'));

		$this->layout = 'ajax';

		return;
		
	}
	
}
