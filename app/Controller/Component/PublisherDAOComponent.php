<?php

App::uses('Component', 'Controller');

class PublisherDAOComponent extends Component {

    public function getByEmail($email) {
        $model = ClassRegistry::init('Publisher');

        $result= $model->find('first', array('conditions' => array('email' => $email), 'recursive' => 1));

        return $result;
    }
}
