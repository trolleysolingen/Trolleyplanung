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
			return $this->redirect(array('controller' => 'start', 'action' => 'index'));
		}
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$mondayThisWeek = strtotime('monday this week');

		$publisher = $this->Session->read('publisher');

		$reservations = $this->ReservationDAO->getReservationsInTimeRange($mondayThisWeek, $publisher["Congregation"]["id"]);

		$timeslots = $this->TimeslotDAO->getAll();

		$this->set("publisher", $this->Session->read('publisher'));
		$this->set("mondayThisWeek", $mondayThisWeek);
		$this->set("timeslots", $timeslots);
		$this->set("reservations", $reservations);
	}


	public function logout() {
		$this->Session->delete('publisher');
		return $this->redirect(array('controller' => 'start', 'action' => 'index'));
	}


	public function addPublisher() {
		$publisher = $this->Session->read('publisher');
		$reservation = $this->ReservationDAO->addPublisher(
							$publisher['Congregation']['id'],
							$this->request->data['reservationDay'],
							$this->request->data['reservationTimeslot'],
							$this->Session->read('publisher'));

		$this->set("reservation", $reservation);
		$this->set("publisher", $publisher);

		$this->set("_serialize", array("reservation", "publisher"));
	}

	public function deletePublisher() {
		$publisher = $this->Session->read('publisher');
		$reservation = $this->ReservationDAO->deletePublisher(
			$publisher['Congregation']['id'],
			$this->request->data['reservationDay'],
			$this->request->data['reservationTimeslot'],
			$publisher,
			$this->request->data['deleteBoth'] == 'true');

		$this->set("reservation", $reservation);
		$this->set("publisher", $publisher);

		$this->set("_serialize", array("reservation", "publisher"));
	}

	public function addGuest() {
		$publisher = $this->Session->read('publisher');
		$reservation = $this->ReservationDAO->addGuest(
			$publisher['Congregation']['id'],
			$this->request->data['reservationDay'],
			$this->request->data['reservationTimeslot'],
			$publisher,
			$this->request->data['guestname']);

		$this->set("reservation", $reservation);
		$this->set("publisher", $publisher);

		$this->set("_serialize", array("reservation", "publisher"));
	}
}
