<?php
App::uses('AppController', 'Controller');
/**
 * Help Controller
 *
 */
class PrivacyController extends AppController {

	public function beforeFilter() {
		
	}
	
	/**
	 * index method
	 *
	 * @return void
	 */
	public function index() {
		$this->set('title_for_layout', 'Datenschutzerklärung');
	}
}