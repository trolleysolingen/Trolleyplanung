<?php
App::uses('AppModel', 'Model');

class Report extends AppModel {
    var $useTable = 'reservations';
    
    public function beforeSave($options = array()) {
    	if (array_key_exists('report_languages_array', $this->data['Report']) && is_array($this->data['Report']['report_languages_array'])) {
    		$this->data['Report']['report_languages'] = implode(",", $this->data['Report']['report_languages_array']);
    	}
    	return true;
    }
}
