<?php
App::uses('AppModel', 'Model');
/**
 * Reservation Model
 *
 * @property Congregation $Congregation
 * @property Timeslot $Timeslot
 */
class PublisherReservation extends AppModel {

	public $belongsTo = array(
			'Publisher' => array(
					'className' => 'Publisher',
					'foreignKey' => 'publisher_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			),
			'Reservation' => array(
					'className' => 'Reservation',
					'foreignKey' => 'reservation_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			)
	);

}
