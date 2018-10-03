<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * Reservations Controller
 *
 */
class ReservationsController extends AppController {

	public $components = array('CongregationDAO', 'ReservationDAO', 'TimeslotDAO', 'DayslotDAO', 'PublisherDAO', 'WeekDay', 'LkwnumberDAO', 'ShipDAO', 'RequestHandler');

	public function beforeFilter() {
		parent::checkLoginPermission();
		parent::checkActiveKillswitch();
		
		if ($this->action != 'logout') {
			parent::checkDataprotection();
		}
		
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
		$routes = $this->CongregationDAO->getRoutesAktiv($publisher["Congregation"]["id"]);

		$reservations = null;
		$timeslots = null;
		$dayslot = null;
		
		$weeksDisplayed = $this->getWeeksDisplayed($routes, $routeId);
		
		$indexRouteId = 0;
		if (sizeof($routes) >= 2) {
			// überprüfe, ob gültige Route
			if (!empty($routeId)) {
				$indexRouteId = $this->getIndexRouteId($routes, $routeId);				
				if ($indexRouteId == -1) {
					$routeId = null;
				}
			} 
		} else if (sizeof($routes) == 1) {
			// nur eine Route für die Versammlung vorhanden
			$routeId = $routes[0]['Routes']['id'];
		}

		if (!empty($routeId)) {
			if (!empty($routes[$indexRouteId]['Routes']['start_date'])) {
				// set the start date to the monday of the route when the route starts
				$mondayWeekRouteStart = $this->last_monday($routes[$indexRouteId]['Routes']['start_date']);
				
				if ($mondayWeekRouteStart > $mondayThisWeek) {
					$mondayThisWeek = $mondayWeekRouteStart;
				}
			}
			
			$reservations = $this->ReservationDAO->getReservationsInTimeRange($mondayThisWeek, $publisher["Congregation"]["id"], $routeId, $weeksDisplayed);
			$timeslots = $this->TimeslotDAO->getAll($publisher['Congregation']['id'], $routeId);
			$dayslot = $this->DayslotDAO->getDayslot($publisher['Congregation']['id'], $routeId);	
			$weekDays = $this->WeekDay->getWeekDays($dayslot, $timeslots);
			$this->set("weekDays", $weekDays);
			
			if ($publisher['Congregation']['show_lkw_numbers']) {
				$lkwnumbers = $this->LkwnumberDAO->getLkwnumbers($routeId);
				$this->set("lkwnumbers", $lkwnumbers);
			}
			
			if ($publisher['Congregation']['typ'] == "Hafen") {
				$ships = $this->ShipDAO->getShips($routeId);
				$this->set("ships", $ships);
			}
		}

		$now = new DateTime('now');

		$publisherList = $this->PublisherDAO->getForAutocompletion($publisher);

		$this->Session->write('routeId', $routeId);
		$this->set("publisher", $this->Session->read('publisher'));
		$this->set("admintools", $this->Session->read('admintools'));
		$this->set("mondayThisWeek", $mondayThisWeek);		
		$this->set("routes", $routes);
		$this->set("routeId", $routeId);
		$this->set("reservations", $reservations);
		$this->set("publisherList", $publisherList);
		$this->set("displayTime", $now->format('Y-m-d H:i:s'));
		$this->set("weeksDisplayed", $weeksDisplayed);

		if($publisher['Congregation']['report']) {
			$this->getMissingReports($publisher);
		}
		$this->set('title_for_layout', 'Schichten');
	}

	private function getIndexRouteId($routes, $routeId) {
		$index = 0;
		foreach ($routes as $route) {
			if ($route['Routes']['id'] == $routeId) {
				return $index;
			}
			$index++;
		}
		return -1;
	}
	
	private function last_monday($date) {
		if (!is_numeric($date)) {
			$date = strtotime($date);
		}
		if (date('w', $date) == 1) {
			return $date;
		} else {
			return strtotime('last monday', $date);
		}
	}

	private function getWeeksDisplayed($routes, $routeId) {
		foreach ($routes as $route) {
			if ($routeId == null || $route['Routes']['id'] == $routeId) {
				return $route['Routes']['weeks_displayed'];
			}
		}
		return 12;
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
							if($reservationPublisher['id'] != $publisher['Publisher']['id'] && ($reservationPublisher['send_mail_for_reservation'] || $reservationPublisher['send_mail_for_reservation'] == null)) {
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
			$missingCongregationReportCount = $this->ReservationDAO->getMissingCongregationReportsCount($publisher);
			$declinedReportList = $this->ReservationDAO->getDeclinedCongregationReports($publisher);
			$adminReportNumber = $missingCongregationReportCount + count($declinedReportList);
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
								if($reservationPublisher['id'] != $publisher['Publisher']['id'] && ($reservationPublisher['send_mail_for_reservation'] || $reservationPublisher['send_mail_for_reservation'] == null)) {
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
			$missingCongregationReportCount = $this->ReservationDAO->getMissingCongregationReportsCount($publisher);
			$declinedReportList = $this->ReservationDAO->getDeclinedCongregationReports($publisher);
			$adminReportNumber = $missingCongregationReportCount + count($declinedReportList);
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
		$subject = $this->Session->read('verwaltungTyp') . "-Schichtplanung";
		if ($deletion) {
			$message = "Liebe(r) " . $reservationPublisher["prename"] . " " . $reservationPublisher["surname"] . ",\n"
				. "\n"
				. "am " . date("d.m.Y", strtotime($reservation['Reservation']['day']))
				. " von " . $reservation['Timeslot']['start']
				. " bis " . $reservation['Timeslot']['end']
				. " Uhr hat sich einer deiner Schichtpartner aus Eurer Schicht gelöscht.\n\n"
				. "Viele Grüße \n"
				. "Deine " . $this->Session->read('verwaltungTyp') . "-Verwaltung \n";
		} else {
			$message = "Liebe(r) " . $reservationPublisher["prename"] . " " . $reservationPublisher["surname"] . ",\n"
				. "\n"
				. "am " . date("d.m.Y", strtotime($reservation['Reservation']['day']))
				. " von " . $reservation['Timeslot']['start']
				. " bis " . $reservation['Timeslot']['end']
				. " Uhr hat sich " . $publisher["Publisher"]["prename"] . " " . $publisher["Publisher"]["surname"]
				. " zu Deiner Schicht hinzugebucht.\n\n"
				. "Viele Grüße \n"
				. "Deine " . $this->Session->read('verwaltungTyp') . "-Verwaltung \n";
		}
		
		if (strpos($reservationPublisher["email"], "@demo.de") === false) {
			$this->sendMail($reservationPublisher["email"], $subject, $message);
		}
	}

	public function sendGuestAlertMail($reservation) {
		$publisher = $this->Session->read('publisher');
		$congregationAdmins = $this->PublisherDAO->getContactPersons($publisher);
		$subject = $this->Session->read('verwaltungTyp') . "-Schichtplanung - Gast-Eintragung";

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
		$subject = $this->Session->read('verwaltungTyp') . "-Schichtplanung - Partner-Eintragung";
		
		$message = "Liebe(r) " . $reservation["GuestPublisher"]["prename"] . " " . $reservation["GuestPublisher"]["surname"] . ",\n"
			. "\n"
			. "Am " . date("d.m.Y", strtotime($reservation['Reservation']['day']))
			. " von " . $reservation['Timeslot']['start']
			. " bis " . $reservation['Timeslot']['end']
			. " Uhr hat dich " . $publisher["Publisher"]["prename"] . " " . $publisher["Publisher"]["surname"]
			. " zu einer Schicht hinzugefügt.\n\n"
			. "Viele Grüße \n"
			. "Deine " . $this->Session->read('verwaltungTyp') . "-Verwaltung \n";
		
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
				$missingCongregationReportCount = $this->ReservationDAO->getMissingCongregationReportsCount($publisher);
				$declinedReportList = $this->ReservationDAO->getDeclinedCongregationReports($publisher);
				$adminReportNumber = $missingCongregationReportCount + count($declinedReportList);
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
				$missingCongregationReportCount = $this->ReservationDAO->getMissingCongregationReportsCount($publisher);
				$declinedReportList = $this->ReservationDAO->getDeclinedCongregationReports($publisher);
				$adminReportNumber = $missingCongregationReportCount + count($declinedReportList);
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
	
	public function saveLkwnumber() {
		$model = ClassRegistry::init('Lkwnumber');
		
		$lkwnumber['Lkwnumber']['route_id'] = $this->request->data['Reservation']['route_id'];
		$lkwnumber['Lkwnumber']['licenseplatenumber'] = $this->request->data['Reservation']['licenseplatenumber'];
		
		if ($model->save($lkwnumber)) {
			$this->Session->setFlash('Das LKW-Nummernschild wurde abgespeichert.', 'default', array('class' => 'alert alert-success'));
			
			$this->LkwnumberDAO->deleteOldLkwnumbers();
			
			return $this->redirect(array('controller' => 'reservations', 'action' => 'index', $this->request->data['Reservation']['route_id']));
		} else {
			$this->Session->setFlash('Das LKW-Nummernschild konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
		}
	}
	
	public function saveShip() {
		$model = ClassRegistry::init('Ship');
		
		$publisher = $this->Session->read('publisher');
	
		$ship['Ship']['route_id'] = $this->request->data['Reservation']['route_id'];
		$ship['Ship']['name'] = $this->request->data['Reservation']['shipname'];
		$ship['Ship']['publisher'] = $publisher["Publisher"]["prename"] . " " . $publisher["Publisher"]["surname"]; 
	
		if ($model->save($ship)) {
			$this->Session->setFlash('Der Schiffsname wurde abgespeichert.', 'default', array('class' => 'alert alert-success'));
				
			$this->ShipDAO->deleteOldShips();
				
			return $this->redirect(array('controller' => 'reservations', 'action' => 'index', $this->request->data['Reservation']['route_id']));
		} else {
			$this->Session->setFlash('Der Schiffsname konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
		}
	}
}
