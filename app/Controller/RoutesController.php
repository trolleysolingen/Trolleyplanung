<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * Routes Controller
 *
 * @property Route $Route
 */
class RoutesController extends AppController {

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Paginator', 'PublisherDAO', 'CongregationDAO');

	public function beforeFilter() {
		parent::checkLoginPermission();
		parent::checkActiveKillswitch();
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

		$this->Route->recursive = 0;
		$this->set('routes',
			$this->Paginator->paginate('Route', array('Route.congregation_id' => $publisher['Congregation']['id'])));

		$this->set('publisher', $publisher);
		$this->set('title_for_layout', 'Routen');
	}

	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function view($id = null) {
		if (!$this->Route->exists($id)) {
			throw new NotFoundException(__('Invalid publisher'));
		}
		$options = array('conditions' => array('Route.' . $this->Route->primaryKey => $id));
		$this->set('route', $this->Route->find('first', $options));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add() {
		$publisher = $this->Session->read('publisher');

		if ($this->request->is('post')) {
			$this->Route->create();

			if ($this->Route->save($this->request->data)) {
				$this->Session->setFlash('Die Route wurde gespeichert.', 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Die Route konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
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

		if (!$this->Route->exists($id)) {
			throw new NotFoundException(__('Ungültige Route'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Route->save($this->request->data)) {
				$this->Session->setFlash('Die Route wurde gespeichert.', 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Die Route konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Route.' . $this->Route->primaryKey => $id));
			$this->request->data = $this->Route->find('first', $options);

			if ($this->request->data['Route']['congregation_id'] != $publisher['Publisher']['congregation_id']) {
				return $this->redirect(array('controller' => 'route', 'action' => 'index'));
			}
		}

		$this->set('publisher', $publisher);
	}

}