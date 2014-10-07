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
	public $components = array('Paginator', 'PublisherDAO', 'RequestHandler');

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

		$this->Publisher->recursive = 0;
		$this->set('publishers',
			$this->Paginator->paginate('Publisher', array('Publisher.congregation_id' => $publisher['Congregation']['id'])));
		$this->set('publisher', $publisher);
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
		$publisher = $this->Session->read('publisher');

		if ($this->request->is('post')) {
			$this->Publisher->create();
			if ($this->Publisher->save($this->request->data)) {
				$this->Session->setFlash(__('The publisher has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The publisher could not be saved. Please, try again.'));
			}
		}
		$roles = $this->Publisher->Role->find('list', array('conditions' => array('name not in' => array('admin', 'guest'))));
		$this->set(compact('congregations', 'roles'));
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

		$roles = $this->Publisher->Role->find('list', array('conditions' => array('name not in' => array('guest',
			($this->request->data && $this->request->data['Role']['name'] == 'admin' ? '' : 'admin')))));
		$this->set(compact('congregations', 'roles'));
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


	public function autocomplete() {
		$publisher = $this->Session->read('publisher');
		$matchingPublishers = $this->PublisherDAO->getByAutocomplete($this->request->query('query'), $publisher);

		$publishersJson = array();
		foreach ($matchingPublishers as $matchingPublisher) {
			$publishersJson[] = array(
				'id' => $matchingPublisher['Publisher']['id'],
				'name' => $matchingPublisher['Publisher']['prename'] . ' ' . $matchingPublisher['Publisher']['surname']);
		}
		$this->set("publishers", $publishersJson);

		$this->set("_serialize", array("publishers"));
	}
}
