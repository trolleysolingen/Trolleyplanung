<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * Congregations Controller
 *
 * @property Congregation $Congregation
 * @property PaginatorComponent $Paginator
 */
class ContactController extends AppController {
	public $components = array('PublisherDAO');
	public $uses = array();

	public function beforeFilter() {
		
	}
		/**
	 * index method
	 *
	 * @return void
	 */
	public function index() {
		$publisher = $this->Session->read('publisher');
		$this->set("publisher", $publisher);
		if($publisher) {
			$contactList = $this->PublisherDAO->getContactPersons($publisher);
			$this->set("contactList", $contactList);
		}
		
		//$email    = new CakeEmail('smtp');
		//$result   = $email->emailFormat('text')
		//					->to('flixmix.bornmann@me.com')
		//					->subject('Welcome to my domain name');
		//$email ->send('smtp');
	}

}