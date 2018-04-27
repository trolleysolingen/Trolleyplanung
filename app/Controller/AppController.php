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
	
	public function checkDataprotection() {
		$publisher = $this->Session->read('publisher');
		
		if (!$publisher['Publisher']['dataprotection']) {
			return $this->redirect(array('controller' => 'dataprotection', 'action' => 'index'));			
		}
				
	}
	
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
		return $this->redirect(array('controller' => 'start', 'action' => 'index', '?' => array('ffd' => $this->Session->read('verwaltungTyp') == 'FFD' ? 'true' : 'false')));
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
	
	public function sendMailBcc($recieverMailArray, $subject, $text) {
		$mail = new CakeEmail('smtp');
		$result = $mail->emailFormat('text')
		->bcc($recieverMailArray)
		->subject($subject);
		return $mail->send($text);
	}
	
	/**
	 * uploads files to the server
	 * @params:
	 *    $folder  = the folder to upload the files e.g. 'img/files'
	 *    $formdata   = the array containing the form files
	 *    $itemId  = id of the item (optional) will create a new sub folder
	 * @return:
	 *    will return an array with the success of each file upload
	 */
	function uploadFiles($folder, $formdata, $filename, $itemId = null)
	{
	   // setup dir names absolute and relative
	   $folder_url = WWW_ROOT.$folder;
	   $rel_url = $folder;
		
	   // create the folder if it does not exist
	   if(!is_dir($folder_url)) {
		  mkdir($folder_url);
	   }
		   
	   // if itemId is set create an item folder
	   if($itemId)
	   {
		  // set new absolute folder
		  $folder_url = WWW_ROOT.$folder.'/'.$itemId; 
		  // set new relative folder
		  $rel_url = $folder.'/'.$itemId;
		  // create directory
		  if(!is_dir($folder_url)) {
			 mkdir($folder_url);
		  }
	   }
		
	   // list of permitted file types, this is only images but documents can be added
	   $permitted = array('image/gif','image/jpeg','image/pjpeg','image/png');
		
	  // assume filetype is false
	  $typeOK = false;
	  // check filetype is ok
	  foreach($permitted as $type)
	  {
		 if($type == $formdata['type']) {
			$typeOK = true;
			break;
		 }
	  }
	  
	  $result[] = array();
	   
	  // if file type ok upload the file
	  if($typeOK) {
		 // switch based on error code
		 switch($formdata['error']) {
			case 0:
				// create full filename
				$full_url = $folder_url.'/'.$filename;
				$url = $rel_url.'/'.$filename;
				// upload the file
				$success = move_uploaded_file($formdata['tmp_name'], $url);
			   
			   // if upload was successful
			   if(!$success) {
				  $result['errors'][] = "Ein Fehler beim hochladen ist aufgetreten. Bitte versuchen Sie es später noch einmal.";
			   }
			   break;
			case 3:
			   // an error occured
			   $result['errors'][] = "Ein Fehler beim hochladen ist aufgetreten. Bitte versuchen Sie es später noch einmal.";
			   break;
			default:
			   // an error occured
			   $result['errors'][] = "Ein Fehler beim hochladen ist aufgetreten. Bitte versuchen Sie es später noch einmal.";
			   break;
		 }
	  } elseif($formdata['error'] == 4) {
		 // no file was selected for upload
		 $result['errors'][] = "Es wurde keine Datei ausgewählt.";
	  } else {
		 // unacceptable file type
		 $result['errors'][] = "Ein Fehler beim hochladen ist aufgetreten. Akzeptierte Dateitypen: gif, jpg, png.";
	  }
	return $result;
	}
}
