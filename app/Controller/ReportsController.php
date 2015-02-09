<?php
App::uses('AppController', 'Controller');
/**
 * Congregations Controller
 *
 * @property Congregation $Congregation
 * @property PaginatorComponent $Paginator
 */
class ReportsController extends AppController {
	public $components = array('CongregationDAO', 'PublisherDAO', 'ReservationDAO', 'RequestHandler');

	public function beforeFilter() {
		$publisher = $this->Session->read('publisher');
		if ($publisher['Congregation']['report'] == 0 || $publisher['Congregation']['report_start_date'] > date("Y-m-d")) {
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
		
		$missingReportList = $this->ReservationDAO->getMissingReports($publisher);
		$this->set("missingReportList", $missingReportList);
		
		$givenReportList = $this->ReservationDAO->getGivenReports($publisher);
		$this->set("givenReportList", $givenReportList);
		
		$this->set('title_for_layout', 'Meine Berichte');
		$this->set("publisher", $this->Session->read('publisher'));
	}
	
	public function edit($id = null) {
		if (!$this->Report->exists($id)) {
			throw new NotFoundException(__('Ungültiger Bericht'));
		}
		if ($this->request->is(array('post', 'put'))) {
			$reservation = $this->request->data;
			$reservation['Report']['minutes'] = $this->request->data['Report']['hours'] * 60 + $this->request->data['Report']['minutes'];
			$reservation['Report']['report_necessary'] = 1;
			$reservation['Report']['no_report_reason'] = null;
			$reservation['Report']['report_date'] = date("Y-m-d");
			
			if ($this->Report->save($reservation)) {
				$this->Session->setFlash('Dein Bericht wurde gespeichert.', 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('controller' => 'reports', 'action' => 'index'));
			} else {
				$this->Session->setFlash('Dein Bericht konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Report.' . $this->Report->primaryKey => $id));
			$givenReport = $this->Report->find('first', $options);
			$this->set("dataMinutes", $givenReport['Report']['minutes']);
			$this->request->data = $givenReport;
		}
	}
	
	public function saveReport() {
		$this->Report->id = $this->request->data['Report']['id'];
		if (!$this->Report->exists()) {
			throw new NotFoundException(__('Ungültiger Bericht'));
		}
		
		$reservation = $this->request->data;
		$publisher = $this->Session->read('publisher');
		
		$reservation['Report']['minutes'] = $this->request->data['Report']['hours'] * 60 + $this->request->data['Report']['minutes'];
		$reservation['Report']['report_necessary'] = 1;
		$reservation['Report']['no_report_reason'] = null;
		$reservation['Report']['reporter_id'] = $publisher['Publisher']['id'];
		$reservation['Report']['report_date'] = date("Y-m-d");
		
		if ($this->Report->save($reservation)) {
			$this->Session->setFlash('Dein Bericht wurde gespeichert.', 'default', array('class' => 'alert alert-success'));
			return $this->redirect(array('controller' => 'reports', 'action' => 'index'));
		} else {
			$this->Session->setFlash('Dein Bericht konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
		}
	}
	
	public function markReportUnnecessary() {
		$this->Report->id = $this->request->data['Report']['id'];
		if (!$this->Report->exists()) {
			throw new NotFoundException(__('Ungültiger Bericht'));
		}
		
		$reservation = $this->request->data;
		
		$reservation['Report']['report_necessary'] = 0;
		$reservation['Report']['reporter_id'] = $publisher['Publisher']['id'];
		$reservation['Report']['report_date'] = date("Y-m-d");
		
		if ($this->Report->save($reservation)) {
			$this->Session->setFlash('Dein Bericht wurde gespeichert.', 'default', array('class' => 'alert alert-success'));
			return $this->redirect(array('controller' => 'reports', 'action' => 'index'));
		} else {
			$this->Session->setFlash('Dein Bericht konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
		}
	}

}