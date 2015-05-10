<?php
App::uses('AppController', 'Controller');
/**
 * Dayslots Controller
 *
 * @property Dayslot $Dayslot
 * @property PaginatorComponent $Paginator
 */
class DayslotsController extends AppController {

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
	public function index($routeId = null) {
		if (!$routeId) {
			return $this->redirect(array('controller' => 'routes', 'action' => 'index'));
		}
		$publisher = $this->Session->read('publisher');

		$dayslot = $this->Dayslot->find('first', array('conditions' => array('congregation_id' => $publisher['Congregation']['id'], 'route_id' => $routeId)));

		if (!$dayslot) {
			$dayslot['Dayslot']['congregation_id'] = $publisher['Congregation']['id'];
			$dayslot['Dayslot']['route_id'] = $routeId;

			$this->Dayslot->create();
			$dayslot = $this->Dayslot->save($dayslot);
		}

		return $this->redirect(array('action' => 'edit', $dayslot['Dayslot']['id']));
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
		if (!$this->Dayslot->exists($id)) {
			throw new NotFoundException(__('Ungültige Schichtzeit'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Dayslot->save($this->request->data)) {
				$this->Session->setFlash('Die Tage wurden gespeichert.', 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'edit', $this->request->data['Dayslot']['id']));
			} else {
				$this->Session->setFlash('Die Tage konnten nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Dayslot.' . $this->Dayslot->primaryKey => $id));
			$this->request->data = $this->Dayslot->find('first', $options);
		}
		$this->set('publisher', $publisher);
	}

}
