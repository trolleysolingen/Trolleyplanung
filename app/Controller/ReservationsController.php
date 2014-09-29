<?php
App::uses('AppController', 'Controller');
/**
 * Reservations Controller
 *
 */
class ReservationsController extends AppController {

	public $components = array('CongregationDAO', 'ReservationDAO', 'TimeslotDAO', 'RequestHandler');

	public function beforeFilter() {
		$publisher = $this->Session->read('publisher');
		if (!$publisher) {
			$congregationPath = $this->params['congregationPath'];
			$congregation = $this->CongregationDAO->getByPath($congregationPath);
			return $this->redirect(array('controller' => 'VS-' . $congregation["Congregation"]["path"]));
		}
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$congregationPath = $this->params['congregationPath'];
		$congregation = $this->CongregationDAO->getByPath($congregationPath);

		$mondayThisWeek = strtotime('monday this week');
		
		$reservations = $this->ReservationDAO->getReservationsInTimeRange($mondayThisWeek, $congregation["Congregation"]["id"]);

		$timeslots = $this->TimeslotDAO->getAll();

		$this->set("congregation", $congregation);
		$this->set("publisher", $this->Session->read('publisher'));
		$this->set("mondayThisWeek", $mondayThisWeek);
		$this->set("timeslots", $timeslots);
		$this->set("reservations", $reservations);
	}


	public function logout() {
		$congregationPath = $this->params['congregationPath'];
		$congregation = $this->CongregationDAO->getByPath($congregationPath);

		$this->Session->delete('publisher');
		return $this->redirect(array('controller' => 'VS-' . $congregation["Congregation"]["path"] . '/start', 'action' => 'index'));
	}


	public function addPublisher() {
		$reservation = $this->ReservationDAO->addPublisher(
							$this->request->data['reservationDay'],
							$this->request->data['reservationTimeslot'],
							$this->Session->read('publisher'));

		$this->set("reservation", $reservation);

		$this->set("_serialize", array("reservation"));
	}

	public function deletePublisher() {
		$reservation = $this->ReservationDAO->deletePublisher(
			$this->request->data['reservationDay'],
			$this->request->data['reservationTimeslot'],
			$this->request->data['publisherNumber']);

		$this->set("reservation", $reservation);

		$this->set("_serialize", array("reservation"));
	}
}
