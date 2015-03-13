<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');
App::uses('Component', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	public $layout = 'bootstrap';
	public $uses = array('PublisherDAO', 'CongregationDAO');
	
	public function checkLoginPermission() {
		$publisher = $this->Session->read('publisher');
		$logout = $this->PublisherDAO->getLoginPermission($publisher);
		if($logout != null) {
			if($logout['Publisher']['log_out']) {
				$this->Session->setFlash('Du wurdest vom Admin ausgeloggt. Bitte melde dich erneut an.', 'default', array('class' => 'alert alert-warning'));
				$this->globalLogout();
			}
		}
	}
	
	public function globalLogout() {
		$this->Session->delete('publisher');
		return $this->redirect(array('controller' => 'start', 'action' => 'index'));
	}
	
	public function checkKillswitch($publisher) {
		$killswitch = $this->CongregationDAO->getKillswitchState($publisher);
		if($killswitch['Congregation']['killswitch'] && $publisher['Role']['name'] != 'admin') {
			$this->Session->setFlash('Im Moment finden Wartungsarbeiten statt. Zur Zeit kannst du dich daher leider nicht einloggen.', 'default', array('class' => 'alert alert-warning'));
			return $this->redirect(array('controller' => 'start', 'action' => 'index'));
		}
	}
	
	public function checkActiveKillswitch() {
		$oneActive = $this->CongregationDAO->getAllKillswitchStates();
		$publisher = $this->Session->read('publisher');
		if($oneActive && $publisher['Role']['name'] == 'admin') {
			$this->Session->setFlash('<strong>ACHTUNG!!!</strong> Mindestens eine Versammlung hat den Killswitch noch aktiviert!!!', 'default', array('class' => 'alert alert-warning'));
		}
	}
	
	public function sendMail($recieverMail, $subject, $text) {
		$mail = new CakeEmail('smtp');
		$result = $mail->emailFormat('text')
			->to($recieverMail)
			->subject($subject);
		if($mail->send($text)) {
			$success = true;
		} else {
			$success = false;
		}
		return $success;
	}
}
