<?php

App::uses('Component', 'Controller');

class PublisherDAOComponent extends Component {

    public function getByEmail($email, $congregationId) {
        $model = ClassRegistry::init('Publisher');

        $result= $model->find('first', array('conditions' => array('email' => $email, 'congregation_id' => $congregationId), 'recursive' => 1));

        return $result;
    }
}
