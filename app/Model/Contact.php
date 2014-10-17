<?php
App::uses('AppModel', 'Model');

class Contact extends AppModel {
    var $useTable = false;
	
	public $validate = array(
		'subject' => array(
			'required'   => true,
			'allowEmpty' => false,
			'on'         => 'create',
			'message'    => 'Du musst einen Betreff eingeben'
		),
		
		'message' => array(
			'required'   => true,
			'allowEmpty' => false,
			'on'         => 'create',
			'message'    => 'Du musst eine Nachricht eingeben'
		)
	);
}
