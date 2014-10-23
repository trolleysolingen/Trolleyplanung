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
	public $components = array('Paginator', 'CongregationDAO');

	public function beforeFilter() {
		$publisher = $this->Session->read('publisher');
		if (!$publisher) {
			return $this->redirect(array('controller' => 'start', 'action' => 'index'));
		} else if ($publisher['Role']['name'] != 'admin') {
			return $this->redirect(array('controller' => 'reservations', 'action' => 'index'));
		}
	}

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index() {
		$this->Congregation->recursive = 0;
		$this->set('congregations', $this->Paginator->paginate());
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
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Congregation->save($this->request->data)) {
				$this->Session->setFlash('Die Versammlung wurde gespeichert.', 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
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
}