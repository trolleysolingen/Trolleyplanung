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
			$publisher = $this->PublisherDAO->getByEmail($email);
			if (sizeof($publisher) == 0) {
				$this->Session->setFlash(__('Die Emailadresse konnte im System nicht gefunden werden.'));
			} else {
				$this->Session->write('publisher', $publisher);
				return $this->redirect(array('controller' => '/reservations', 'action' => 'index'));
			}
		}
	}

}