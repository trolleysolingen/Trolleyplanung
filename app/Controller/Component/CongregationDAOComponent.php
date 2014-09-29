<?php

App::uses('Component', 'Controller');

class CongregationDAOComponent extends Component {

    public function getByPath($path) {
        $model = ClassRegistry::init('Congregation');

        $result= $model->find('first', array('conditions' => array('path' => $path), 'recursive' => 0));

        if (sizeof($result) == 0) {
            // find default congregation (== Solingen)
            $result= $model->find('first', array('conditions' => array('path' => 'Solingen'), 'recursive' => 0));
        }
        return $result;
    }
}
