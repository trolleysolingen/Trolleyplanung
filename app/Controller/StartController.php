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
					$this->Session->write('admintools', false);
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
					} else {
						$this->Session->write('adminReportNumber', "");
						$this->Session->write('publisherReports', "");			
					}
					return $this->redirect(array('controller' => 'reservations', 'action' => 'index'));
				}
			} else {
				$this->Session->setFlash('Bitte gib deine E-Mail-Adresse und dein Passwort ein.', 'default', array('class' => 'alert alert-danger'));
			}

		}
		$this->set('title_for_layout', 'Trolleyverwaltung');
	}

}