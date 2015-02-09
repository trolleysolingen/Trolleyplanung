<?php
App::uses('AppModel', 'Model');
/**
 * Reservation Model
 *
 * @property Congregation $Congregation
 * @property Timeslot $Timeslot
 * @property Publisher1 $Publisher1
 * @property Publisher2 $Publisher2
 */
class Reservation extends AppModel {

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = array(
		'congregation_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'day' => array(
			'date' => array(
				'rule' => array('date'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'timeslot_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'publisher1_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				'allowEmpty' => true,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'publisher2_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				'allowEmpty' => true,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'Congregation' => array(
			'className' => 'Congregation',
			'foreignKey' => 'congregation_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Timeslot' => array(
			'className' => 'Timeslot',
			'foreignKey' => 'timeslot_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Publisher1' => array(
			'className' => 'Publisher',
			'foreignKey' => 'publisher1_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Publisher2' => array(
			'className' => 'Publisher',
			'foreignKey' => 'publisher2_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Reporter' => array(
			'className' => 'Publisher',
			'foreignKey' => 'reporter_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);
}
