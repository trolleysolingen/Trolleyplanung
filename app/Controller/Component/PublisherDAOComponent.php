<?php

App::uses('Component', 'Controller');

class PublisherDAOComponent extends Component {

    public function getByEmail($email) {
        $model = ClassRegistry::init('Publisher');

        $result= $model->find('first', array('conditions' => array('email' => $email), 'recursive' => 1));

        return $result;
    }

    public function getByAutocomplete($query, $publisher) {
        $model = ClassRegistry::init('Publisher');
        $result = $model->find('all', array(
            'fields' => array('Publisher.id', 'Publisher.prename', 'Publisher.surname'),
            'recursive' => -1,
            'conditions' => array(
                'Publisher.id !=' => $publisher['Publisher']['id'],
                'Publisher.congregation_id' => $publisher['Congregation']['id'],
                'Publisher.role_id !=' => '3',
                'OR' => array('Publisher.prename LIKE' => $query . '%', 'Publisher.surname LIKE' => $query . '%'))
            )
        );

        return $result;
    }


    public function getByName($name, $publisher) {
        $model = ClassRegistry::init('Publisher');

        $result = $model->find('first', array(
                'recursive' => -1,
                'conditions' => array(
                    'Publisher.id !=' => $publisher['Publisher']['id'],
                    'Publisher.congregation_id' => $publisher['Congregation']['id'],
                    'CONCAT(Publisher.prename, \' \', Publisher.surname)' => $name
                )
            )
        );

        return $result;
    }

    public function getGuestPublisher() {
        $model = ClassRegistry::init('Publisher');

        $result = $model->find('first', array(
                'conditions' => array(
                    'Role.name' => 'guest'
                ),
                'recursive' => 0
            )
        );

        return $result;
    }
}
