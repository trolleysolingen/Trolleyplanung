<?php
App::uses('AppController', 'Controller');
/**
 * Help Controller
 *
 */
class HelpController extends AppController {

	var $uses = false;

	public function beforeFilter() {

		$publisher = $this->Session->read('publisher');
		if (!$publisher) {
			return $this->redirect(array('controller' => 'start', 'action' => 'index'));
		}
		parent::checkDataprotection();
		$this->set("publisher", $publisher);
	}
	/**
	 * index method
	 *
	 * @return void
	 */
	public function index() {
		$this->set('title_for_layout', 'Hilfe');
	}

}