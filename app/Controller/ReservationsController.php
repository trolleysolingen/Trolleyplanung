<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * Reservations Controller
 *
 */
class ReservationsController extends AppController {

	public $components = array('CongregationDAO', 'ReservationDAO', 'TimeslotDAO', 'PublisherDAO', 'RequestHandler');

	public function beforeFilter() {
		$publisher = $this->Session->read('publisher');
		if (!$publisher) {
			if (!$this->request->is('ajax')) {
				return $this->redirect(array('controller' => 'start', 'action' => 'index'));
			}
		}
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		//$mondayThisWeek = strtotime('monday this week');
		$mondayThisWeek = strtotime(date('o-\\WW'));

		$publisher = $this->Session->read('publisher');

		$now = new DateTime('now');
		$reservations = $this->ReservationDAO->getReservationsInTimeRange($mondayThisWeek, $publisher["Congregation"]["id"]);

		$timeslots = $this->TimeslotDAO->getAll($publisher);

		$publisherList = $this->PublisherDAO->getForAutocompletion($publisher);

		$this->set("publisher", $this->Session->read('publisher'));
		$this->set("mondayThisWeek", $mondayThisWeek);
		$this->set("timeslots", $timeslots);
		$this->set("reservations", $reservations);
		$this->set("publisherList", $publisherList);
		$this->set("displayTime", $now->format('Y-m-d H:i:s'));
	}


	public function logout() {
		$this->Session->delete('publisher');
		return $this->redirect(array('controller' => 'start', 'action' => 'index'));
	}


	public function addPublisher() {
		$publisher = $this->Session->read('publisher');

		$reservation = null;
		if ($publisher) {
			$reservation = $this->ReservationDAO->addPublisher(
				$publisher['Congregation']['id'],
				$this->request->data['reservationDay'],
				$this->request->data['reservationTimeslot'],
				$this->request->data['displayTime'],
				$this->Session->read('publisher'));

			if (array_key_exists("sendMail", $reservation) && $reservation["sendMail"]) {
				try {
					$this->sendReservationMailToPublisher($reservation, false);
				} catch (Exception $e) {
				}
			}
		}
		$this->set("reservation", $reservation);
		$this->set("publisher", $publisher);
		$now = new DateTime('now');
		$this->set("displayTime", $now->format('Y-m-d H:i:s'));

		$this->set("_serialize", array("reservation", "publisher", "displayTime"));
	}

	public function deletePublisher() {
		$publisher = $this->Session->read('publisher');

		$reservation = null;
		if ($publisher) {
			$reservation = $this->ReservationDAO->deletePublisher(
				$publisher['Congregation']['id'],
				$this->request->data['reservationDay'],
				$this->request->data['reservationTimeslot'],
				$publisher,
				$this->request->data['deleteBoth'] == 'true');

			if (array_key_exists("sendMail", $reservation) && $reservation["sendMail"]) {
				try {
					$this->sendReservationMailToPublisher($reservation, true);
				} catch (Exception $e) {
				}
			}
		}
		$this->set("reservation", $reservation);
		$this->set("publisher", $publisher);
		$now = new DateTime('now');
		$this->set("displayTime", $now->format('Y-m-d H:i:s'));

		$this->set("_serialize", array("reservation", "publisher", "displayTime"));
	}

	public function addGuest() {
		$publisher = $this->Session->read('publisher');

		$reservation = null;
		if ($publisher) {
			$reservation = $this->ReservationDAO->addGuest(
				$publisher['Congregation']['id'],
				$this->request->data['reservationDay'],
				$this->request->data['reservationTimeslot'],
				$this->request->data['displayTime'],
				$publisher,
				$this->request->data['guestname']);

			if (array_key_exists("sendMail", $reservation) && $reservation["sendMail"]) {
				try {
					$this->sendGuestAlertMail($reservation, $publisher);
				} catch (Exception $e) {
				}
			}
		}

		$this->set("reservation", $reservation);
		$this->set("publisher", $publisher);
		$now = new DateTime('now');
		$this->set("displayTime", $now->format('Y-m-d H:i:s'));

		$this->set("_serialize", array("reservation", "publisher", "displayTime"));
	}

	public function sendReservationMailToPublisher($reservation, $deletion) {
		$subject = "Trolley-Schichtplanung";
		if ($deletion) {
			$message = "Liebe(r) " . $reservation["Publisher1"]["prename"] . " " . $reservation["Publisher1"]["surname"] . ",\n"
				. "\n"
				. "am " . date("d.m.Y", strtotime($reservation['Reservation']['day']))
				. " von " . $reservation['Timeslot']['start']
				. " bis " . $reservation['Timeslot']['end']
				. " Uhr hat sich Dein Schichtpartner aus Eurer Schicht gelöscht.\n\n"
				. "Viele Grüße \n"
				. "Deine Trolleyverwaltung \n";
		} else {
			$message = "Liebe(r) " . $reservation["Publisher1"]["prename"] . " " . $reservation["Publisher1"]["surname"] . ",\n"
				. "\n"
				. "am " . date("d.m.Y", strtotime($reservation['Reservation']['day']))
				. " von " . $reservation['Timeslot']['start']
				. " bis " . $reservation['Timeslot']['end']
				. " Uhr hat sich " . $reservation["Publisher2"]["prename"] . " " . $reservation["Publisher2"]["surname"]
				. " zu Deiner Schicht hinzugebucht.\n\n"
				. "Viele Grüße \n"
				. "Deine Trolleyverwaltung \n";
		}

		if (strpos($reservation["Publisher1"]["email"], "@demo.de") === false) {
			$mail = new CakeEmail('smtp');
			$result = $mail->emailFormat('text')
				->to($reservation["Publisher1"]["email"])
				->subject($subject);

			$mail->send($message);
		}
	}

	public function sendGuestAlertMail($reservation, $publisher) {
		$congregationAdmins = $this->PublisherDAO->getContactPersons($publisher);
		$subject = "Trolley-Schichtplanung - Gast-Eintragung";

		$message = "Am " . date("d.m.Y", strtotime($reservation['Reservation']['day']))
			. " von " . $reservation['Timeslot']['start']
			. " bis " . $reservation['Timeslot']['end']
			. " Uhr hat " . $reservation["Publisher1"]["prename"] . " " . $reservation["Publisher1"]["surname"]
			. " einen Gast-Verkündiger hinzugefügt: " . $reservation["Reservation"]["guestname"];

		$mail = new CakeEmail('smtp');

		foreach($congregationAdmins as $congregationAdmin) {
			$mail->addTo($congregationAdmin['Publisher']['email']);
		}

		$result = $mail->emailFormat('text')
			->subject($subject);

		$mail->send($message);

	}
}
