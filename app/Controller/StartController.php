<?php
App::uses('AppController', 'Controller');
/**
 * Congregations Controller
 *
 * @property Congregation $Congregation
 * @property PaginatorComponent $Paginator
 */
class StartController extends AppController {
	public $components = array('CongregationDAO', 'PublisherDAO');

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
			$publisher = $this->PublisherDAO->getByEmail($email, $password);
			if (sizeof($publisher) == 0) {
				$this->Session->setFlash(__('Der Login war nicht erfolgreich. Bitte überprüfe E-Mail-Adresse und Passwort.'));
			} else {
				$this->Session->write('publisher', $publisher);
				return $this->redirect(array('controller' => '/reservations', 'action' => 'index'));
			}
		}
		$this->set('title_for_layout', 'Trolleyverwaltung');
	}

}