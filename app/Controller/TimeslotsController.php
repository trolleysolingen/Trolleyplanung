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
	public $components = array('Paginator', 'PublisherDAO', 'CongregationDAO');
	
	public $paginate = array(
			'order' => array(
					'Timeslot.start' => 'asc'
			)
	);

	public function beforeFilter() {
		parent::checkLoginPermission();
		parent::checkActiveKillswitch();
		parent::checkDataprotection();
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
		$publisher = $this->Session->read('publisher');
		
		$day = array_key_exists('day', $this->params['named']) ? $this->params['named']['day'] : null;
		if (!$routeId || !$day) {			
			return $this->redirect(array('controller' => 'congregations', 'action' => 'edit', $publisher['Congregation']['id']));
		}
		

		$this->Timeslot->recursive = 0;
		
		$this->Paginator->settings = $this->paginate;
		$this->set('timeslots', $this->Paginator->paginate('Timeslot', array(
						'Timeslot.congregation_id' => $publisher['Congregation']['id'], 
						'Timeslot.route_id' => $routeId, 
						'Timeslot.day' => $day)));
		
		$modelRoute = ClassRegistry::init('Route');
		$route = $modelRoute->find('first', array(
				'conditions' => array(
						'Route.id' => $routeId
				),
				'recursive' => -1
		)
				);
		$this->set('route', $route);
		
		$this->set('routeId', $routeId);
		$this->set('day', $day);
		$daysDisplay = array('monday' => 'Montag', 'tuesday' => 'Dienstag', 'wednesday' => 'Mittwoch', 'thursday' => 'Donnerstag', 'friday' => 'Freitag', 'saturday' => 'Samstag', 'sunday' => 'Sonntag');
		$this->set('dayDisplay', $daysDisplay[$day]);
		
		$this->set('publisher', $publisher);
		$this->set('title_for_layout', 'Schichtzeiten');
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
			throw new NotFoundException(__('Ungültige Schichtzeit'));
		}
		$options = array('conditions' => array('Timeslot.' . $this->Timeslot->primaryKey => $id));
		$this->set('timeslot', $this->Timeslot->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add($routeId = null) {
		$day = array_key_exists('day', $this->params['named']) ? $this->params['named']['day'] : null;
		
		$publisher = $this->Session->read('publisher');
		if ($this->request->is('post')) {
			$this->Timeslot->create();
			if ($this->Timeslot->save($this->request->data)) {
				$this->Session->setFlash('Die Schichtzeit wurde gespeichert.', 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index', $this->request->data['Timeslot']['route_id'], 'day' =>  $this->request->data['Timeslot']['day']));
			} else {
				$this->Session->setFlash('Die Schichtzeit konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));

			}
		} else {
			if (!$routeId || !$day) {			
				return $this->redirect(array('controller' => 'routes', 'action' => 'index'));
			}
		}
		$this->set('routeId', $routeId);
		$this->set('day', $day);
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
			throw new NotFoundException(__('Ungültige Schichtzeit'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Timeslot->save($this->request->data)) {
				$this->Session->setFlash('Die Schichtzeit wurde gespeichert.', 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index', $this->request->data['Timeslot']['route_id'], 'day' =>  $this->request->data['Timeslot']['day']));
			} else {
				$this->Session->setFlash('Die Schichtzeit konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
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
			throw new NotFoundException(__('Ungültige Schichtzeit'));
		}
		$options = array('conditions' => array('Timeslot.' . $this->Timeslot->primaryKey => $id));
		$timeslot = $this->Timeslot->find('first', $options);
		if ($this->Timeslot->delete()) {
			$this->Session->setFlash('Die Schichtzeit wurde gelöscht.', 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash('Die Schichtzeit konnte nicht gelöscht werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index', $timeslot['Timeslot']['route_id'], 'day' =>  $timeslot['Timeslot']['day']));
	}
}
