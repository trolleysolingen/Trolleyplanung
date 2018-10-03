<?php
App::uses('AppController', 'Controller');
/**
 * Congregations Controller
 *
 * @property Congregation $Congregation
 * @property PaginatorComponent $Paginator
 */
class StartController extends AppController {
	public $components = array('CongregationDAO', 'PublisherDAO', 'ReservationDAO');

	public function beforeFilter() {

		$publisher = $this->Session->read('publisher');
		if ($publisher) {
			return $this->redirect(array('controller' => 'reservations', 'action' => 'index'));
		}
	}
	/**
	 * index method
	 *
	 * @return void
	 */
	public function index() {
		if ($this->request->is('post')) {
			$email = $this->request->data["Start"]["email"];
			$password = $this->request->data["Start"]["password"];
			if (!empty($email) && !empty($password)) {
				$publisher = $this->PublisherDAO->getByEmail($email, $password);
				if (sizeof($publisher) == 0) {
					$this->Session->setFlash('Der Login war nicht erfolgreich. Bitte überprüfe E-Mail-Adresse und Passwort.', 'default', array('class' => 'alert alert-danger'));
				} else {
					$this->checkKillswitch($publisher);
					$this->PublisherDAO->setLoginPermission($publisher);
					$publisher = $this->PublisherDAO->getByEmail($email, $password);
					$this->Session->write('publisher', $publisher);
					$this->Session->write('verwaltungTyp', $publisher['Congregation']['typ']);
					$this->Session->write('admintools', false);
					//$this->Session->write('Config.language', 'eng');
					if($publisher['Congregation']['report']) {
						if($publisher['Role']['name'] == 'admin' || $publisher['Role']['name'] == 'congregation admin') {
							$missingCongregationReportCount = $this->ReservationDAO->getMissingCongregationReportsCount($publisher);
							$declinedReportList = $this->ReservationDAO->getDeclinedCongregationReports($publisher);
							$adminReportNumber = $missingCongregationReportCount + count($declinedReportList);
							if($adminReportNumber > 0) {
								$this->Session->write('adminReportNumber', $adminReportNumber);
							} else {
								$this->Session->write('adminReportNumber', "");
							}
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
					} else {
						$this->Session->write('adminReportNumber', "");
						$this->Session->write('publisherReports', "");			
					}					
					
					return $this->redirect(array('controller' => 'reservations', 'action' => 'index'));
				}
			} else {
				$this->Session->setFlash('Bitte gib deine E-Mail-Adresse und dein Passwort ein.', 'default', array('class' => 'alert alert-danger'));
			}

		} else {			
			// not logged in; decide from hostname or url params if to display trolley or ffd or hafen		
			$hostname = $_SERVER['HTTP_HOST'];
			if ((strlen($hostname) >= 3 && substr( $hostname, 0, 3 ) === "ffd") || (array_key_exists('ffd', $this->params['url']) && $this->params['url']['ffd'] == "true")) {
				$this->set('title_for_layout', 'FFD-Verwaltung');
				$this->Session->write('verwaltungTyp', 'FFD');
			} else if ((strlen($hostname) >= 5 && substr( $hostname, 0, 5 ) === "hafen") || (array_key_exists('Hafen', $this->params['url']) && $this->params['url']['Hafen'] == "true")) {
				$this->set('title_for_layout', 'Hafen-Verwaltung');
				$this->Session->write('verwaltungTyp', 'Hafen');
			} else {
				$this->set('title_for_layout', 'Trolleyverwaltung');
				$this->Session->write('verwaltungTyp', 'Trolley');
			}			
		}		
	}

}