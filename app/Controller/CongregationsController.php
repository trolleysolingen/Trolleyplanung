<?php
App::uses('AppController', 'Controller');
/**
 * Congregations Controller
 *
 * @property Congregation $Congregation
 * @property PaginatorComponent $Paginator
 */
class CongregationsController extends AppController {
	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Paginator', 'CongregationDAO', 'PublisherDAO', 'RequestHandler');
	public $uses = array('Congregation', 'Publisher');

	public function beforeFilter() {
		parent::checkLoginPermission();
		parent::checkActiveKillswitch();
		$publisher = $this->Session->read('publisher');
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		if (!$publisher) {
			return $this->redirect(array('controller' => 'start', 'action' => 'index'));
		} //declare in following else if ALL methods that the congregation admin can call from edit!!!
		else if (strpos($actual_link,'edit') === false && strpos($actual_link,'switchModuleStatus') === false && strpos($actual_link,'changeReportDate') === false){
			if ($publisher['Role']['name'] == 'congregation admin') {
				return $this->redirect(array('controller' => 'congregations', 'action' => 'edit', $publisher['Congregation']['id']));
			}
		} else if ($publisher['Role']['name'] != 'admin' && $publisher['Role']['name'] != 'congregation admin'){ 
			return $this->redirect(array('controller' => 'reservations', 'action' => 'index'));
		}
		$this->set('publisher', $publisher);
	}

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index() {
		$this->Congregation->recursive = 0;
		$this->set('congregations', $this->Paginator->paginate());
		$this->set('title_for_layout', 'Versammlungen');
	}

	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function view($id = null) {
		if (!$this->Congregation->exists($id)) {
			throw new NotFoundException(__('Ungültige Versammlung'));
		}
		$options = array('conditions' => array('Congregation.' . $this->Congregation->primaryKey => $id));
		$this->set('congregation', $this->Congregation->find('first', $options));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Congregation->create();
			if ($this->Congregation->save($this->request->data)) {
				$this->Session->setFlash('Die Versammlung wurde gespeichert.', 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Die Versammlung konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
			}
		}
	}
	
	/**
	 * add method
	 *
	 * @return void
	 */
	public function addpublisher($congregationId) {
		$publisher = $this->Session->read('publisher');
	
		if ($this->request->is('post')) {
			$this->Publisher->create();
			$newPublisher = $this->request->data;
	
			if ($this->Publisher->save($newPublisher)) {
				$this->Session->setFlash('Der Verkündiger wurde gespeichert.', 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('controller' => 'congregations', 'action' => 'index'));
			} else {
				$this->Session->setFlash('Der Verkündiger konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
			}
		}
		$roles = $this->Publisher->Role->find('list', array('fields' => array('id', 'description'), 'conditions' => array('name not in' => array('admin', 'guest'))));
		$this->set(compact('roles'));
		$this->set('publisher', $publisher);
		$this->set('congregationId', $congregationId);
	}

	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function edit($id = null) {
		if (!$this->Congregation->exists($id)) {
			throw new NotFoundException(__('Ungültige Versammlung'));
		}
		$publisher = $this->Session->read('publisher');
		$this->loadModel('Route');
		$this->Route->recursive = 0;
		$this->set('routes', $this->Paginator->paginate('Route', array('Route.congregation_id' => $publisher['Congregation']['id'])));
		
		if ($this->request->is(array('post', 'put')) && isset($this->request->data['editSubmit'])) {
			if ($this->Congregation->save($this->request->data)) {
				$publisher2 = $this->PublisherDAO->getById($publisher);
				$this->Session->write('publisher', $publisher2);
				$this->Session->write('verwaltungTyp', $publisher2['Congregation']['typ']);
				$this->Session->setFlash('Die Versammlung wurde gespeichert.', 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('controller' => 'congregations', 'action' => 'edit', $publisher['Congregation']['id']));
			} else {
				$this->Session->setFlash('Die Versammlung konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Congregation.' . $this->Congregation->primaryKey => $id));
			$this->request->data = $this->Congregation->find('first', $options);
		}
	}

	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function delete($id = null) {
		$this->Congregation->id = $id;
		if (!$this->Congregation->exists()) {
			throw new NotFoundException(__('Ungültige Versammlung'));
		}
		if ($this->Congregation->delete()) {
			$this->Session->setFlash('Die Versammlung wurde gelöscht.', 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash('Die Versammlung konnte nicht gelöscht werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	public function switchModuleStatus($id = null, $module) {
		if (!$this->Congregation->exists($id)) {
			throw new NotFoundException(__('Ungültige Versammlung'));
		}
		
		$options = array('conditions' => array('Congregation.' . $this->Congregation->primaryKey => $id));
		$congregation = $this->Congregation->find('first', $options);
		
		if($congregation['Congregation'][$module]) {
			$congregation['Congregation'][$module] = 0;
			if($module == "report") {
				$congregation['Congregation']['report_start_date'] = null;
			}
		} else {
			$congregation['Congregation'][$module] = 1;
		}
		
		if ($this->Congregation->save($congregation)) {
			$this->Session->setFlash('Deine Änderung wurde gespeichert', 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash('Deine Änderung konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
		}
		
		$publisher = $this->Session->read('publisher');
		$publisher2 = $this->PublisherDAO->getById($publisher);
		$this->Session->write('publisher', $publisher2);
		
		return $this->redirect(array('controller' => 'congregations', 'action' => 'edit', $publisher['Congregation']['id']));
	}
	
	public function changeReportDate() {
		$publisher = $this->Session->read('publisher');
		
		$options = array('conditions' => array('Congregation.' . $this->Congregation->primaryKey => $publisher['Congregation']['id']));
		$congregation = $this->Congregation->find('first', $options);
		
		$congregation['Congregation']['report_start_date'] = date("Y-m-d", strtotime($this->request->data['reportDate']));

		$this->Congregation->save($congregation);
		
		$this->switchModuleStatus($publisher['Congregation']['id'], "report");
	}
}
