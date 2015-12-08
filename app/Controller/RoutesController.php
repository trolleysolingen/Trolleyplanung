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
				return $this->redirect(array('controller' => 'congregations', 'action' => 'edit', $publisher['Congregation']['id']));
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
				return $this->redirect(array('controller' => 'congregations', 'action' => 'edit', $publisher['Congregation']['id']));
			} else {
				$this->Session->setFlash('Die Route konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Route.' . $this->Route->primaryKey => $id));
			$this->request->data = $this->Route->find('first', $options);

			if ($this->request->data['Route']['congregation_id'] != $publisher['Publisher']['congregation_id']) {
				return $this->redirect(array('controller' => 'congregations', 'action' => 'edit', $publisher['Congregation']['id']));
			}
		}

		$this->set('publisher', $publisher);
	}
	
	public function deleteMap($id = null) {
		
		$publisher = $this->Session->read('publisher');
		$file = glob('img/routes/route_' . $id . '.*');
		
		if (unlink($file[0])) {
			$success = true;
		} else {
			$success = false;
		}

		if ($success) {
			$this->Session->setFlash('Die Karte wurde gelöscht.', 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash('Die Karte konnte nicht gelöscht werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('controller' => 'congregations', 'action' => 'edit', $publisher['Congregation']['id']));
	}
	
	public function uploadMap() {
		$publisher = $this->Session->read('publisher');
		$filename = "route_" . $this->data['Files']['id'];
		$formdata = $this->data['Files']['upload'];
		if($formdata['type'] == "image/gif") {
			$filename .= ".gif";
		} else if($formdata['type'] == "image/jpeg") {
			$filename .= ".jpg";
		} else if($formdata['type'] == "image/pjpeg") {
			$filename .= ".jpeg";
		} else {
			$filename .= ".png";
		}
		
		$result = $this->uploadFiles('img/routes', $formdata, $filename);
		return $this->redirect(array('controller' => 'congregations', 'action' => 'edit', $publisher['Congregation']['id']));
	}

}