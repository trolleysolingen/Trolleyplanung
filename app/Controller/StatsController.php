<?php
App::uses('AppController', 'Controller');
/**
 * Congregations Controller
 *
 * @property Congregation $Congregation
 * @property PaginatorComponent $Paginator
 */
class StatsController extends AppController {
	public $components = array('CongregationDAO', 'PublisherDAO', 'ReservationDAO', 'CongregationDAO');

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
	public function index() {	
		$monthArray = array();
		$monthArray = $this->getMonthList();
		$this->set("monthArray", $monthArray);
		if ($this->request->is('post')) {
			$this->getStatForMonth($this->request->data["Stat"]["month"]);
		} else {
			$this->getStatForMonth(date('Y-m-01'));
		}
	}
	
	public function getStatForMonth($date) {
		$publisher = $this->Session->read('publisher');
		
		$allReservations = $this->ReservationDAO->getAllReservationsPerMonth($publisher, $date);
		$this->set("allReservations", $allReservations);
		
		$givenReportList = $this->ReservationDAO->getGivenCongregationReportsPerMonth($publisher, $date);
		$this->set("givenReportList", $givenReportList);
		
		$missingReportList = $this->ReservationDAO->getMissingCongregationReportsPerMonth($publisher, $date);
		$this->set("missingReportList", $missingReportList);
		
		$declinedReportList = $this->ReservationDAO->getDeclinedCongregationReportsPerMonth($publisher, $date);
		$this->set("declinedReportList", $declinedReportList);
		
		$monthMinutes = 0;
		$monthBooks = 0;
		$monthMagazines = 0;
		$monthBrochures = 0;
		$monthTracts = 0;
		$monthVideos = 0;
		$monthJworgcard = 0;
		$monthConversations = 0;
		$monthContacts = 0;
		
		foreach($givenReportList as $givenReport) {
			$monthMinutes = $monthMinutes + $givenReport['Reservation']['minutes'];
			$monthBooks = $monthBooks + $givenReport['Reservation']['books'];
			$monthMagazines = $monthMagazines + $givenReport['Reservation']['magazines'];
			$monthBrochures = $monthBrochures + $givenReport['Reservation']['brochures'];
			$monthTracts = $monthTracts + $givenReport['Reservation']['tracts'];
			$monthVideos = $monthVideos + $givenReport['Reservation']['videos'];
			$monthJworgcard = $monthJworgcard + $givenReport['Reservation']['jworgcard'];
			$monthConversations = $monthConversations + $givenReport['Reservation']['conversations'];
			$monthContacts = $monthContacts + $givenReport['Reservation']['contacts'];
		}
		
		$report = array();
		$newDate = date("m.Y", strtotime($date));
		$report[0] = "Bücher " . $newDate . "\t" .  $monthBooks;
		$report[1] = "Zeitschriften " . $newDate . "\t" .  $monthMagazines;
		$report[2] = "Broschüren " . $newDate . "\t" .  $monthBrochures;
		$report[3] = "Traktate " . $newDate . "\t" .  $monthTracts;
		$report[4] = "Videos " . $newDate . "\t" .  $monthVideos;
		$report[5] = "Visitenkarten " . $newDate . "\t" .  $monthJworgcard;
		$report[6] = "Gespräche " . $newDate . "\t" .  $monthConversations;
		$report[7] = "Kontaktdaten erhalten " . $newDate . "\t" .  $monthContacts;
		
		settype($monthMinutes, 'integer');
		$monthHours = floor($monthMinutes / 60);
		$monthMinutes = ($monthMinutes % 60);
		$this->set("monthHours", $monthHours);
		$this->set("monthMinutes", $monthMinutes);
		$this->set("report", $report);
	}
	
	public function getMonthList() {
		$publisher = $this->Session->read('publisher');
		$startDate = strtotime($publisher['Congregation']['report_start_date']);
		$endDate   = strtotime(date('Y-m-01'));

		$currentDate = $endDate;
		
		$monthArray = array();

		while ($currentDate >= $startDate) {
			$month = "";
			switch (date('m',$currentDate)) {
				case '01':
					$month = "Januar";
					break;
				case '02':
					$month = "Februar";
					break;
				case '03':
					$month = "März";
					break;
				case '04':
					$month = "April";
					break;
				case '05':
					$month = "Mai";
					break;
				case '06':
					$month = "Juni";
					break;
				case '07':
					$month = "Juli";
					break;
				case '08':
					$month = "August";
					break;
				case '09':
					$month = "September";
					break;
				case '10':
					$month = "Oktober";
					break;
				case '11':
					$month = "November";
					break;
				case '12':
					$month = "Dezember";
					break;
			}
			$monthArray[date('Y-m-d',$currentDate)] = $month . " " . date('Y',$currentDate);
			$currentDate = strtotime( date('Y-m-01',$currentDate).' -1 month');
		}
		return $monthArray;
	}
}