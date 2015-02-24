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
			$publisher = $this->Session->read('publisher');
		
			$reservation = $this->request->data;
			$reservation['Report']['minutes'] = $this->request->data['Report']['hours'] * 60 + $this->request->data['Report']['minutes'];
			$reservation['Report']['report_necessary'] = 1;
			$reservation['Report']['no_report_reason'] = null;
			$reservation['Report']['report_date'] = date("Y-m-d");
			$reservation['Report']['reporter_id'] = $publisher['Publisher']['id'];
			
			if ($this->Report->save($reservation)) {
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
			return $this->redirect($this->referer());
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
		$publisher = $this->Session->read('publisher');
		
		$reservation['Report']['report_necessary'] = 0;
		$reservation['Report']['reporter_id'] = $publisher['Publisher']['id'];
		$reservation['Report']['report_date'] = date("Y-m-d");
		
		if ($this->Report->save($reservation)) {
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
			return $this->redirect($this->referer());
		} else {
			$this->Session->setFlash('Dein Bericht konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
		}
	}
	
	public function admin() {
		$publisher = $this->Session->read('publisher');
		if (!$publisher) {
			return $this->redirect(array('controller' => 'start', 'action' => 'index'));
		} else if ($publisher['Role']['name'] != 'admin' && $publisher['Role']['name'] != 'congregation admin') {
			return $this->redirect(array('controller' => 'reservations', 'action' => 'index'));
		}
		
		$missingCongregationReportList = $this->ReservationDAO->getMissingCongregationReports($publisher);
		$this->set("missingCongregationReportList", $missingCongregationReportList);
		
		$declinedReportList = $this->ReservationDAO->getDeclinedCongregationReports($publisher);
		$this->set("declinedReportList", $declinedReportList);
	}
	
	public function markReportUnnecessaryAdmin() {
		$this->Report->id = $this->request->data['Report']['id'];
		if (!$this->Report->exists()) {
			throw new NotFoundException(__('Ungültiger Bericht'));
		}
		
		$reservation = $this->request->data;
		$publisher = $this->Session->read('publisher');
		
		$reservation['Report']['report_necessary'] = 0;
		$reservation['Report']['no_report_reason'] = null;
		$reservation['Report']['reporter_id'] = $publisher['Publisher']['id'];
		$reservation['Report']['report_date'] = date("Y-m-d");
		
		if ($this->Report->save($reservation)) {
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
			return $this->redirect($this->referer());
		} else {
			$this->Session->setFlash('Dein Bericht konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
		}
	}
	
	public function remindPublisher($id1, $id2, $reservationId, $oneTime) {
	
		$success = true;
	
		$publisher = $this->Session->read('publisher');
		
		$publisher1 = $this->PublisherDAO->getByRealId($id1);
		
		$reservation = $this->ReservationDAO->getByRealId($reservationId);
		
		$subject = "Fehlender Bericht";
		$message1 = "Liebe(r) " . $publisher1["Publisher"]["prename"] . " " . $publisher1["Publisher"]["surname"] . ",\n"
		."\n"
		."Bitte gib einen Bericht für deine Trolleyschicht am " . date("d.m.Y", strtotime($reservation['Reservation']['day'])) . " von " . $reservation['Timeslot']['start'] . " - " . $reservation['Timeslot']['end'] . " Uhr ab. Bitte sprecht euch untereinander ab, ob du oder dein Partner den Bericht abgibt. Weitere Informationen findest du, wenn du dich in die Trolleyverwaltung unter: http://trolley.jw-center.com/ einloggst.\n"
		."Vielen Dank!\n"
		."\n"
		."Deine Trolleyverwaltung";
		
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		if (strpos($actual_link,'trolleydemo') === false) {
			if (strpos($publisher1["Publisher"]["email"], "@demo.de") === false) {
				$success = $this->sendMail($publisher1["Publisher"]["email"], $subject, $message1);
			}
			
			$publisher2 = $this->PublisherDAO->getByRealId($id2);
			
			if($id2 != 0 && $id2 != 1 && !empty($publisher2["Publisher"]["email"])) {
				
				$message2 = "Liebe(r) " . $publisher2["Publisher"]["prename"] . " " . $publisher2["Publisher"]["surname"] . ",\n"
				."\n"
				."Bitte gib einen Bericht für deine Trolleyschicht am " . date("d.m.Y", strtotime($reservation['Reservation']['day'])) . " von " . $reservation['Timeslot']['start'] . " - " . $reservation['Timeslot']['end'] . " Uhr ab. Bitte sprecht euch untereinander ab, ob du oder dein Partner den Bericht abgibt. Weitere Informationen findest du, wenn du dich in die Trolleyverwaltung unter: http://trolley.jw-center.com/ einloggst.\n"
				."Vielen Dank!\n"
				."\n"
				."Deine Trolleyverwaltung";
				
				if (strpos($publisher2["Publisher"]["email"], "@demo.de") === false) {
					$success = $this->sendMail($publisher2["Publisher"]["email"], $subject, $message2);
				}
			}
		}
		if($oneTime) {
			if(!$success) {
				$this->Session->setFlash('Die Erinnerung konnte nicht verschickt werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
			} else {
				$this->Session->setFlash('Die Erinnerung wurde verschickt.', 'default', array('class' => 'alert alert-success'));
			}
			$this->redirect(array('action' => 'admin'));
		} else {
			return $success;
		}
	}
	
	public function acceptDeclinedReport($id, $oneTime) {
		$options = array('conditions' => array('Report.' . $this->Report->primaryKey => $id));
		$reservation = $this->Report->find('first', $options);
		$publisher = $this->Session->read('publisher');
		
		$reservation['Report']['report_necessary'] = 0;
		$reservation['Report']['no_report_reason'] = null;
		
		$success = $this->Report->save($reservation);
		
		if($oneTime) {		
			if ($success) {
				$this->Session->setFlash('Verweigerter Bericht wurde akzeptiert.', 'default', array('class' => 'alert alert-success'));
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
				return $this->redirect(array('action' => 'admin'));
			} else {
				$this->Session->setFlash('Der Bericht konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			return $success;
		}
	}
	
	public function reopenReport() {
		$this->Report->id = $this->request->data['Report']['id'];
		if (!$this->Report->exists()) {
			throw new NotFoundException(__('Ungültiger Bericht'));
		}
		
		$reservation = $this->request->data;
		$publisher = $this->Session->read('publisher');
		
		$reservation['Report']['report_necessary'] = 1;
		
		if ($this->Report->save($reservation)) {
			$this->Session->setFlash('Der Bericht wurde dem Verkündiger zum ausfüllen zurückgegeben.', 'default', array('class' => 'alert alert-success'));
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
			return $this->redirect(array('action' => 'admin'));
		} else {
			$this->Session->setFlash('Es ist etwas schief gelaufen. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
		}
	}
	
	public function saveManualReport() {		
		$reservation = $this->request->data;
		$publisher = $this->Session->read('publisher');
		
		$reservation['Report']['congregation_id'] = $publisher['Congregation']['id'];
		$reservation['Report']['minutes'] = $this->request->data['Report']['hours'] * 60 + $this->request->data['Report']['minutes'];
		$reservation['Report']['report_necessary'] = 1;
		$reservation['Report']['no_report_reason'] = null;
		$reservation['Report']['reporter_id'] = $publisher['Publisher']['id'];
		$reservation['Report']['report_date'] = date("Y-m-d");
		
		$reservation['Report']['day'] = date("Y-m-d", strtotime($reservation['Report']['day']));
		
		if ($this->Report->save($reservation)) {
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
			return $this->redirect(array('action' => 'admin'));
		} else {
			$this->Session->setFlash('Dein Bericht konnte nicht gespeichert werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
		}
	}
	
	public function sendMultipleReminders() {		
		$publisher = $this->Session->read('publisher');
		
		$missingCongregationReportList = $this->ReservationDAO->getMissingCongregationReports($publisher);
		
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		if (strpos($actual_link,'trolleydemo') === false) {
			$error = 0;
			foreach ($missingCongregationReportList as $reportToSendAccount) {
				if($reportToSendAccount['Reservation']['publisher2_id'] == null) {
					$id2 = 0;
				} else {
					$id2 = $reportToSendAccount['Reservation']['publisher2_id'];
				}
				$success = $this->remindPublisher($reportToSendAccount['Reservation']['publisher1_id'], $id2, $reportToSendAccount['Reservation']['id'], false);
				
				if(!$success) {
					$error++;
				}
			}
			
			if($error>0) {
				$this->Session->setFlash('Die Erinnerungen konnten nicht verschickt werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
			} else {
				$this->Session->setFlash('Die Erinnerungen wurden verschickt.', 'default', array('class' => 'alert alert-success'));
			}
			
			$this->redirect(array('action' => 'admin'));
		} else {
			$this->redirect(array('action' => 'admin'));
		}
	}
	
	public function acceptMultipleDeclinedReports() {
		$publisher = $this->Session->read('publisher');
		$declinedReportList = $this->ReservationDAO->getDeclinedCongregationReports($publisher);
		$error = 0;
		foreach ($declinedReportList as $reportToAccept) {
			$success = $this->acceptDeclinedReport($reportToAccept['Reservation']['id'], false);
			
			if(!$success) {
				$error++;
			}
		}
		
		if($error>0) {
			$this->Session->setFlash('Die Berichte konnten nicht bearbeitet werden. Bitte versuche es später nochmal.', 'default', array('class' => 'alert alert-danger'));
		} else {
			$this->Session->setFlash('Die Berichte wurden verarbeitet.', 'default', array('class' => 'alert alert-success'));
		}
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
		$this->redirect(array('action' => 'admin'));
	}
}