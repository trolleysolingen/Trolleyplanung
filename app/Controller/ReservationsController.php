<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * Reservations Controller
 *
 */
class ReservationsController extends AppController {

	public $components = array('CongregationDAO', 'ReservationDAO', 'TimeslotDAO', 'DayslotDAO', 'PublisherDAO', 'RequestHandler');

	public function beforeFilter() {
		parent::checkLoginPermission();
		parent::checkActiveKillswitch();
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
	public function index($routeId = null) {
		//$mondayThisWeek = strtotime('monday this week');
		$mondayThisWeek = strtotime(date('o-\\WW'));

		$publisher = $this->Session->read('publisher');
		$routes = $this->CongregationDAO->getRoutes($publisher["Congregation"]["id"]);

		$reservations = null;
		$timeslots = null;
		$dayslot = null;
		if (sizeof($routes) >= 2) {
			// überprüfe, ob gültige Route
			if (!empty($routeId) && !$this->isValidRouteId($routes, $routeId)) {
				$routeId = null;
			}
		} else if (sizeof($routes) == 1) {
			// nur eine Route für die Versammlung vorhanden
			$routeId = $routes[0]['Routes']['id'];
		}

		if (!empty($routeId)) {
			$reservations = $this->ReservationDAO->getReservationsInTimeRange($mondayThisWeek, $publisher["Congregation"]["id"], $routeId);
			$timeslots = $this->TimeslotDAO->getAll($publisher['Congregation']['id'], $routeId);
			$dayslot = $this->DayslotDAO->getDayslot($publisher['Congregation']['id'], $routeId);
		}

		$now = new DateTime('now');

		$publisherList = $this->PublisherDAO->getForAutocompletion($publisher);

		$this->Session->write('routeId', $routeId);
		$this->set("publisher", $this->Session->read('publisher'));
		$this->set("admintools", $this->Session->read('admintools'));
		$this->set("mondayThisWeek", $mondayThisWeek);
		$this->set("timeslots", $timeslots);
		$this->set("dayslot", $dayslot);
		$this->set("routes", $routes);
		$this->set("routeId", $routeId);
		$this->set("reservations", $reservations);
		$this->set("publisherList", $publisherList);
		$this->set("displayTime", $now->format('Y-m-d H:i:s'));

		if($publisher['Congregation']['report']) {
			$this->getMissingReports($publisher);
		}
		$this->set('title_for_layout', 'Schichten');
	}

	private function isValidRouteId($routes, $routeId) {
		foreach ($routes as $route) {
			if ($route['Routes']['id'] == $routeId) {
				return true;
			}
		}
		return false;
	}

	public function logout() {
		$this->globalLogout();
	}


	public function addPublisher() {
		$publisher = $this->Session->read('publisher');
		$routeId = $this->Session->read('routeId');

		$reservation = null;
		if ($publisher) {
			$reservation = $this->ReservationDAO->addPublisher(
				$publisher['Congregation']['id'],
				$routeId,
				$this->request->data['reservationDay'],
				$this->request->data['reservationTimeslot'],
				$this->request->data['displayTime'],
				$this->Session->read('publisher'));
				
				$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				if (strpos($actual_link,'trolleydemo') === false) {
					if (array_key_exists("sendMail", $reservation) && $reservation["sendMail"]) {
						try {
							foreach($reservation['Publisher'] as $reservationPublisher) {
								if($reservationPublisher['id'] != $publisher['Publisher']['id']) {
									$this->sendReservationMailToPublisher($reservationPublisher, $reservation, false);
								}
							}
						} catch (Exception $e) {
					}
				}
			}
		}
		$this->set("reservation", $reservation);
		$this->set("publisher", $publisher);
		$now = new DateTime('now');
		$this->set("displayTime", $now->format('Y-m-d H:i:s'));

		$this->set("_serialize", array("reservation", "publisher", "displayTime"));
		
		if($publisher['Congregation']['report']) {
			$missingCongregationReportList = $this->ReservationDAO->getMissingCongregationReports($publisher);
			$declinedReportList = $this->ReservationDAO->getDeclinedCongregationReports($publisher);
			$adminReportNumber = count($missingCongregationReportList) + count($declinedReportList);
			if($adminReportNumber > 0) {
				$this->Session->write('adminReportNumber', $adminReportNumber);
			} else {
				$this->Session->write('adminReportNumber', "");
			}
			
			$missingReports = $this->ReservationDAO->getMissingReports($publisher);
			$publisherReports = count($missingReports);
			if($publisherReports > 0) {
				$this->Session->write('publisherReports', $publisherReports);
			} else {
				$this->Session->write('publisherReports', "");
			}
		}
	}

	public function deletePublisher() {
		$publisher = $this->Session->read('publisher');
		$routeId = $this->Session->read('routeId');

		$reservation = null;
		if ($publisher) {
			$reservation = $this->ReservationDAO->deletePublisher(
				$publisher['Congregation']['id'],
				$routeId,
				$this->request->data['reservationDay'],
				$this->request->data['reservationTimeslot'],
				$this->request->data['deletePartners']);
				
				$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				if (strpos($actual_link,'trolleydemo') === false) {
					if (array_key_exists("sendMail", $reservation) && $reservation["sendMail"]) {
						try {
							foreach($reservation['Publisher'] as $reservationPublisher) {
								if($reservationPublisher['id'] != $publisher['Publisher']['id']) {
									$this->sendReservationMailToPublisher($reservationPublisher, $reservation, true);
								}
							}
						} catch (Exception $e) {
					}
				}
			}
		}
		$this->set("reservation", $reservation);
		$this->set("publisher", $publisher);
		$now = new DateTime('now');
		$this->set("displayTime", $now->format('Y-m-d H:i:s'));

		$this->set("_serialize", array("reservation", "publisher", "displayTime"));
		
		if($publisher['Congregation']['report']) {
			$missingCongregationReportList = $this->ReservationDAO->getMissingCongregationReports($publisher);
			$declinedReportList = $this->ReservationDAO->getDeclinedCongregationReports($publisher);
			$adminReportNumber = count($missingCongregationReportList) + count($declinedReportList);
			if($adminReportNumber > 0) {
				$this->Session->write('adminReportNumber', $adminReportNumber);
			} else {
				$this->Session->write('adminReportNumber', "");
			}
			
			$missingReports = $this->ReservationDAO->getMissingReports($publisher);
			$publisherReports = count($missingReports);
			if($publisherReports > 0) {
				$this->Session->write('publisherReports', $publisherReports);
			} else {
				$this->Session->write('publisherReports', "");
			}
		}
	}

	public function addGuest() {
		$publisher = $this->Session->read('publisher');
		$routeId = $this->Session->read('routeId');

		$reservation = null;
		if ($publisher) {
			$reservation = $this->ReservationDAO->addGuest(
				$publisher['Congregation']['id'],
				$routeId,
				$this->request->data['reservationDay'],
				$this->request->data['reservationTimeslot'],
				$this->request->data['displayTime'],
				$publisher,
				$this->request->data['guestname']);
				
			$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			if (strpos($actual_link,'trolleydemo') === false) {
				if (array_key_exists("sendMail", $reservation) && $reservation["sendMail"]) {
					try {
						$this->sendGuestAlertMail($reservation);
					} catch (Exception $e) {
					
					}
				} else if(array_key_exists("send_mail_when_partner", $reservation) && $reservation["send_mail_when_partner"]) {
					$this->sendPartnerMail($reservation);
				}
			}
		}

		$this->set("reservation", $reservation);
		$this->set("publisher", $publisher);
		$now = new DateTime('now');
		$this->set("displayTime", $now->format('Y-m-d H:i:s'));

		$this->set("_serialize", array("reservation", "publisher", "displayTime"));
	}

	public function sendReservationMailToPublisher($reservationPublisher, $reservation, $deletion) {
		$publisher = $this->Session->read('publisher');
		$subject = "Trolley-Schichtplanung";
		if ($deletion) {
			$message = "Liebe(r) " . $reservationPublisher["prename"] . " " . $reservationPublisher["surname"] . ",\n"
				. "\n"
				. "am " . date("d.m.Y", strtotime($reservation['Reservation']['day']))
				. " von " . $reservation['Timeslot']['start']
				. " bis " . $reservation['Timeslot']['end']
				. " Uhr hat sich einer deiner Schichtpartner aus Eurer Schicht gelöscht.\n\n"
				. "Viele Grüße \n"
				. "Deine Trolleyverwaltung \n";
		} else {
			$message = "Liebe(r) " . $reservationPublisher["prename"] . " " . $reservationPublisher["surname"] . ",\n"
				. "\n"
				. "am " . date("d.m.Y", strtotime($reservation['Reservation']['day']))
				. " von " . $reservation['Timeslot']['start']
				. " bis " . $reservation['Timeslot']['end']
				. " Uhr hat sich " . $publisher["Publisher"]["prename"] . " " . $publisher["Publisher"]["surname"]
				. " zu Deiner Schicht hinzugebucht.\n\n"
				. "Viele Grüße \n"
				. "Deine Trolleyverwaltung \n";
		}

		if (strpos($reservationPublisher["email"], "@demo.de") === false) {
			$this->sendMail($reservationPublisher["email"], $subject, $message);
		}
	}

	public function sendGuestAlertMail($reservation) {
		$publisher = $this->Session->read('publisher');
		$congregationAdmins = $this->PublisherDAO->getContactPersons($publisher);
		$subject = "Trolley-Schichtplanung - Gast-Eintragung";

		$message = "Am " . date("d.m.Y", strtotime($reservation['Reservation']['day']))
			. " von " . $reservation['Timeslot']['start']
			. " bis " . $reservation['Timeslot']['end']
			. " Uhr hat " . $publisher["Publisher"]["prename"] . " " . $publisher["Publisher"]["surname"]
			. " einen Gast-Verkündiger hinzugefügt: " . $reservation["guestName"];

		foreach($congregationAdmins as $congregationAdmin) {
			$this->sendMail($congregationAdmin['Publisher']['email'], $subject, $message);
		}
	}
	
	public function sendPartnerMail($reservation) {
		$publisher = $this->Session->read('publisher');
		$subject = "Trolley-Schichtplanung - Partner-Eintragung";
		
		$message = "Liebe(r) " . $reservation["GuestPublisher"]["prename"] . " " . $reservation["GuestPublisher"]["surname"] . ",\n"
			. "\n"
			. "Am " . date("d.m.Y", strtotime($reservation['Reservation']['day']))
			. " von " . $reservation['Timeslot']['start']
			. " bis " . $reservation['Timeslot']['end']
			. " Uhr hat dich " . $publisher["Publisher"]["prename"] . " " . $publisher["Publisher"]["surname"]
			. " zu einer Schicht hinzugef�gt.\n\n"
			. "Viele Grüße \n"
			. "Deine Trolleyverwaltung \n";
			
		$this->sendMail($reservation['GuestPublisher']['email'], $subject, $message);
	}
	
	public function getMissingReports($publisher) {
		$missingReports = $this->ReservationDAO->getMissingReports($publisher);
		$this->set("missingReports", $missingReports);
	}
	
	public function saveReport() {
		$this->Reservation->id = $this->request->data['Reservation']['id'];
		if (!$this->Reservation->exists()) {
			throw new NotFoundException(__('Ungültige Schicht'));
		}
		
		$reservation = $this->request->data;
		$publisher = $this->Session->read('publisher');
		
		$reservation['Reservation']['minutes'] = $this->request->data['Reservation']['hours'] * 60 + $this->request->data['Reservation']['minutes'];
		$reservation['Reservation']['report_necessary'] = 1;
		$reservation['Reservation']['no_report_reason'] = null;
		$reservation['Reservation']['reporter_id'] = $publisher['Publisher']['id'];
		$reservation['Reservation']['report_date'] = date("Y-m-d");
		
		if ($this->Reservation->save($reservation)) {
			$this->Session->setFlash('Dein Bericht wurde gespeichert.', 'default', array('class' => 'alert alert-success'));
			if($publisher['Congregation']['report']) {
				$missingCongregationReportList = $this->ReservationDAO->getMissingCongregationReports($publisher);
				$declinedReportList = $this->ReservationDAO->getDeclinedCongregationReports($publisher);
				$adminReportNumber = count($missingCongregationReportList) + count($declinedReportList);
				if($adminReportNumber > 0) {
					$this->Session->write('adminReportNumber', $adminReportNumber);
				} else {
					$this->Session->write('adminReportNumber', "");
				}
				
				$missingReports = $this->ReservationDAO->getMissingReports($publisher);
				$publisherReports = count($missingReports);
				if($publisherReports > 0) {
					$this->Session->write('publisherReports', $publisherReports);
				} else {
					$this->Session->write('publisherReports', "");
				}
			}
			return $this->redirect(array('controller' => 'reservations', 'action' => 'index'));
		} else {
			$this->Session->setFlash('Dein Bericht konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
		}
	}
	
	public function markReportUnnecessary() {
		$this->Reservation->id = $this->request->data['Reservation']['id'];
		if (!$this->Reservation->exists()) {
			throw new NotFoundException(__('Ungültige Schicht'));
		}
		
		$reservation = $this->request->data;
		$publisher = $this->Session->read('publisher');
		
		$reservation['Reservation']['report_necessary'] = 0;
		$reservation['Reservation']['reporter_id'] = $publisher['Publisher']['id'];
		$reservation['Reservation']['report_date'] = date("Y-m-d");
		
		if ($this->Reservation->save($reservation)) {
			$this->Session->setFlash('Dein Bericht wurde gespeichert.', 'default', array('class' => 'alert alert-success'));
			if($publisher['Congregation']['report']) {
				$missingCongregationReportList = $this->ReservationDAO->getMissingCongregationReports($publisher);
				$declinedReportList = $this->ReservationDAO->getDeclinedCongregationReports($publisher);
				$adminReportNumber = count($missingCongregationReportList) + count($declinedReportList);
				if($adminReportNumber > 0) {
					$this->Session->write('adminReportNumber', $adminReportNumber);
				} else {
					$this->Session->write('adminReportNumber', "");
				}
				
				$missingReports = $this->ReservationDAO->getMissingReports($publisher);
				$publisherReports = count($missingReports);
				if($publisherReports > 0) {
					$this->Session->write('publisherReports', $publisherReports);
				} else {
					$this->Session->write('publisherReports', "");
				}
			}
			return $this->redirect(array('controller' => 'reservations', 'action' => 'index'));
		} else {
			$this->Session->setFlash('Dein Bericht konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
		}
	}
	
	public function toggleAdminTools($adminRouteId) {
		$admintools = $this->Session->read('admintools');
		$this->Session->write('admintools', !$admintools);
		$this->Session->setFlash('Der Status wurde geändert', 'default', array('class' => 'alert alert-success'));
		return $this->redirect(array('controller' => 'reservations', 'action' => 'index', $adminRouteId));
	}
}
