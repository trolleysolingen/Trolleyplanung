<?php

App::uses('Component', 'Controller');

class WeekDayComponent extends Component {

    public function getWeekDays($dayslot, $timeslots) {
    	$weekDays  = array();
    	$countActiveDays = 0;
    	$maxTimeslots = 0;
    	    	
    	if (sizeof($dayslot) > 0 && $dayslot['Dayslot']['monday'] == 1) {
    		$timeslotsWeekday = $this->getTimeslots($timeslots, 'monday');
    		array_push($weekDays, array('day' => 'monday', 'display' => 'Montag', 'timeslots' => $timeslotsWeekday));
    		$countActiveDays++;
    		$maxTimeslots = max($maxTimeslots, sizeof($timeslotsWeekday));
    	} else {
    		array_push($weekDays, null);
    	}
    	
    	if (sizeof($dayslot) > 0 && $dayslot['Dayslot']['tuesday'] == 1) {
    		$timeslotsWeekday = $this->getTimeslots($timeslots, 'tuesday');
    		array_push($weekDays, array('day' => 'tuesday', 'display' =>  'Dienstag', 'timeslots' => $timeslotsWeekday));
    		$countActiveDays++;
    		$maxTimeslots = max($maxTimeslots, sizeof($timeslotsWeekday));
    	} else {
    		array_push($weekDays, null);
    	}
    	
    	if (sizeof($dayslot) > 0 && $dayslot['Dayslot']['wednesday'] == 1) {
    		$timeslotsWeekday = $this->getTimeslots($timeslots, 'wednesday');
    		array_push($weekDays, array('day' => 'wednesday', 'display' => 'Mittwoch', 'timeslots' => $timeslotsWeekday));
    		$countActiveDays++;
    		$maxTimeslots = max($maxTimeslots, sizeof($timeslotsWeekday));
    	} else {
    		array_push($weekDays, null);
    	}
    	
    	if (sizeof($dayslot) > 0 && $dayslot['Dayslot']['thursday'] == 1) {
    		$timeslotsWeekday = $this->getTimeslots($timeslots, 'thursday');
    		array_push($weekDays, array('day' => 'thursday', 'display' => 'Donnerstag', 'timeslots' => $timeslotsWeekday));
    		$countActiveDays++;
    		$maxTimeslots = max($maxTimeslots, sizeof($timeslotsWeekday));
    	} else {
    		array_push($weekDays, null);
    	}
    	
    	if (sizeof($dayslot) > 0 && $dayslot['Dayslot']['friday'] == 1) {
    		$timeslotsWeekday = $this->getTimeslots($timeslots, 'friday');
    		array_push($weekDays, array('day' => 'friday', 'display' => 'Freitag', 'timeslots' => $timeslotsWeekday));
    		$countActiveDays++;
    		$maxTimeslots = max($maxTimeslots, sizeof($timeslotsWeekday));
    	} else {
    		array_push($weekDays, null);
    	}
    	
    	if (sizeof($dayslot) > 0 && $dayslot['Dayslot']['saturday'] == 1) {
    		$timeslotsWeekday = $this->getTimeslots($timeslots, 'saturday');
    		array_push($weekDays, array('day' => 'saturday', 'display' => 'Samstag', 'timeslots' => $timeslotsWeekday));
    		$countActiveDays++;
    		$maxTimeslots = max($maxTimeslots, sizeof($timeslotsWeekday));
    	} else {
    		array_push($weekDays, null);
    	}
    	
    	if (sizeof($dayslot) > 0 && $dayslot['Dayslot']['sunday'] == 1) {
    		$timeslotsWeekday = $this->getTimeslots($timeslots, 'sunday');
    		array_push($weekDays, array('day' => 'sunday', 'display' => 'Sonntag', 'timeslots' => $timeslotsWeekday));
    		$countActiveDays++;
    		$maxTimeslots = max($maxTimeslots, sizeof($timeslotsWeekday));
    	} else {
    		array_push($weekDays, null);
    	}   	
    	
        return array('weekDays'=> $weekDays, 'activeDays' => $countActiveDays, 'maxTimeslots' => $maxTimeslots);
    }
    
    private function getTimeslots($timeslots, $weekDay) {
    	$timeslotsWeekday = array();
    	
    	$found = false;
    	for ($slot = 0; $slot < sizeof($timeslots); $slot++) {
    		$timeslotDay = $timeslots[$slot]['Timeslot']['day'];
    		
    		if ($timeslotDay == $weekDay) {
    			array_push($timeslotsWeekday, $timeslots[$slot]);
    			$found = true;
    		} else {
    			if ($found) { // da timeslots nach weekDays sortiert sind, braucht nicht weitergesucht werden
    				break;
    			}
    		}
    	}
    	
    	return $timeslotsWeekday;
    }
}
