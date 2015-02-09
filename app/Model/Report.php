<?php
App::uses('AppModel', 'Model');

class Report extends AppModel {
    var $useTable = 'reservations';
	
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
