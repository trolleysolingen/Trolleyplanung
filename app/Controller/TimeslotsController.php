<?php
App::uses('AppController', 'Controller');
/**
 * Timeslots Controller
 *
 * @property Timeslot $Timeslot
 * @property PaginatorComponent $Paginator
 */
class TimeslotsController extends AppController {

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
		} else if ($publisher['Role']['name'] != 'admin' && $publisher['Role']['name'] != 'congregation admin') {
			return $this->redirect(array('controller' => 'reservations', 'action' => 'index'));
		}
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$publisher = $this->Session->read('publisher');

		$this->Timeslot->recursive = 0;
		$this->set('timeslots',
			$this->Paginator->paginate('Timeslot', array('Timeslot.congregation_id' => $publisher['Congregation']['id'])));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Timeslot->exists($id)) {
			throw new NotFoundException(__('Invalid timeslot'));
		}
		$options = array('conditions' => array('Timeslot.' . $this->Timeslot->primaryKey => $id));
		$this->set('timeslot', $this->Timeslot->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$publisher = $this->Session->read('publisher');
		if ($this->request->is('post')) {
			$this->Timeslot->create();
			if ($this->Timeslot->save($this->request->data)) {
				$this->Session->setFlash(__('The timeslot has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The timeslot could not be saved. Please, try again.'));
			}
		}
		$this->set('publisher', $publisher);
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$publisher = $this->Session->read('publisher');
		if (!$this->Timeslot->exists($id)) {
			throw new NotFoundException(__('Invalid timeslot'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Timeslot->save($this->request->data)) {
				$this->Session->setFlash(__('The timeslot has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The timeslot could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Timeslot.' . $this->Timeslot->primaryKey => $id));
			$this->request->data = $this->Timeslot->find('first', $options);
		}
		$this->set('publisher', $publisher);
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Timeslot->id = $id;
		if (!$this->Timeslot->exists()) {
			throw new NotFoundException(__('Invalid timeslot'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Timeslot->delete()) {
			$this->Session->setFlash(__('The timeslot has been deleted.'));
		} else {
			$this->Session->setFlash(__('The timeslot could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
