<?php
App::uses('AppController', 'Controller');
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
	public $components = array('Paginator', 'CongregationDAO');

	public function beforeFilter() {
		$publisher = $this->Session->read('publisher');
		if (!$publisher) {
			$congregationPath = $this->params['congregationPath'];
			$congregation = $this->CongregationDAO->getByPath($congregationPath);
			return $this->redirect(array('controller' => 'VS-' . $congregation["Congregation"]["path"]));
		} else if ($publisher['Role']['name'] != 'admin') {
			return $this->redirect(array('controller' => 'VS-' . $publisher["Congregation"]["path"] . '/reservations', 'action' => 'index'));
		}
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Publisher->recursive = 0;
		$this->set('publishers', $this->Paginator->paginate());
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
		if ($this->request->is('post')) {
			$this->Publisher->create();
			if ($this->Publisher->save($this->request->data)) {
				$this->Session->setFlash(__('The publisher has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The publisher could not be saved. Please, try again.'));
			}
		}
		$congregations = $this->Publisher->Congregation->find('list');
		$roles = $this->Publisher->Role->find('list');
		$this->set(compact('congregations', 'roles'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Publisher->exists($id)) {
			throw new NotFoundException(__('Invalid publisher'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Publisher->save($this->request->data)) {
				$this->Session->setFlash(__('The publisher has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The publisher could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Publisher.' . $this->Publisher->primaryKey => $id));
			$this->request->data = $this->Publisher->find('first', $options);
		}
		$congregations = $this->Publisher->Congregation->find('list');
		$roles = $this->Publisher->Role->find('list');
		$this->set(compact('congregations', 'roles'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Publisher->id = $id;
		if (!$this->Publisher->exists()) {
			throw new NotFoundException(__('Invalid publisher'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Publisher->delete()) {
			$this->Session->setFlash(__('The publisher has been deleted.'));
		} else {
			$this->Session->setFlash(__('The publisher could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
