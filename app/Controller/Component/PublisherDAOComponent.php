<?php

App::uses('Component', 'Controller');

class PublisherDAOComponent extends Component {

	public function getLoginPermission($publisher) {
        $model = ClassRegistry::init('Publisher');
        $result = $model->find('first', array(
                'fields' => array('Publisher.log_out'),
                'recursive' => -1,
                'conditions' => array(
                    'Publisher.id' => $publisher['Publisher']['id']
                )
            )
        );

        return $result;
    }
	
	public function setLoginPermission($publisher) {
		$now = new DateTime('now');
		$db = ConnectionManager::getDataSource('default');
		$db->rawQuery('UPDATE publishers SET log_out=0, last_login=\'' . $now->format('Y-m-d H:i:s') . '\' WHERE id=' . $publisher['Publisher']['id']);
	}
	
	public function logoutAllUsers() {
		$db = ConnectionManager::getDataSource('default');
		$db->rawQuery('UPDATE publishers SET log_out=1');
	}

    public function getByEmail($email, $password) {
        $model = ClassRegistry::init('Publisher');

        $result= $model->find('first', array('conditions' => array('email' => $email, 'password' => $password), 'recursive' => 1));

        return $result;
    }
	
	public function getById($publisher) {
        $model = ClassRegistry::init('Publisher');

        $result= $model->find('first', array('conditions' => array('Publisher.id' => $publisher['Publisher']['id']), 'recursive' => 1));

        return $result;
    }
	
	public function getByRealId($id) {
        $model = ClassRegistry::init('Publisher');

        $result= $model->find('first', array('conditions' => array('Publisher.id' => $id), 'recursive' => 1));

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
                    'OR' => array('Publisher.prename LIKE' => $query . '%', 'Publisher.surname LIKE' => $query . '%')
                )
            )
        );

        return $result;
    }

    public function getForAutocompletion($publisher) {
        $model = ClassRegistry::init('Publisher');
        $result = $model->find('all', array(
                'fields' => array('Publisher.id', 'Publisher.prename', 'Publisher.surname'),
                'recursive' => -1,
                'conditions' => array(
                    'Publisher.id !=' => $publisher['Publisher']['id'],
                    'Publisher.congregation_id' => $publisher['Congregation']['id'],
                    'Publisher.role_id !=' => '3'
                )
            )
        );

        return $result;
    }
	
	public function getAllMailAdresses() {
        $model = ClassRegistry::init('Publisher');
        $result = $model->find('all', array(
                'recursive' => 1,
                'conditions' => array(
                    'Publisher.email !=' => ''
                )
            )
        );

        return $result;
    }
    
    public function getAllMailAdressesDataProtection() {
    	$model = ClassRegistry::init('Publisher');
    	$result = $model->find('all', array(
    			'recursive' => 1,
    			'conditions' => array(
    					'Publisher.email !=' => '',
    					'Publisher.dataprotection' => 0
    			)
    	)
    			);
    
    	return $result;
    }
	
	public function getAllCongMailAdresses($publisher) {
        $model = ClassRegistry::init('Publisher');
        $result = $model->find('all', array(
                'recursive' => 1,
                'conditions' => array(
                    'Publisher.congregation_id' => $publisher['Congregation']['id'],
                    'Publisher.email !=' => ''
                )
            )
        );

        return $result;
    }
    
    public function getAllCongMailAdressesDataProtection($publisher) {
    	$model = ClassRegistry::init('Publisher');
    	$result = $model->find('all', array(
    			'recursive' => 1,
    			'conditions' => array(
    					'Publisher.congregation_id' => $publisher['Congregation']['id'],
    					'Publisher.email !=' => '',
    					'Publisher.dataprotection' => 0
    			)
    	)
    			);
    
    	return $result;
    }
	
	public function getAllCongAdminMailAdresses($publisher) {
        $model = ClassRegistry::init('Publisher');
        $result = $model->find('all', array(
                'recursive' => 1,
				'conditions' => array(
					'OR' => array(
						'AND' => array(
							array('Publisher.congregation_id' => $publisher['Congregation']['id']),
							array('Publisher.email !=' => ''),
							array('Publisher.role_id =' => 2)
						),
						'OR' => array(
							array(
								'AND' => array(
									array('Publisher.congregation_id' => $publisher['Congregation']['id']),
									array('Publisher.email !=' => ''),
									array('Publisher.role_id =' => 4)
								)
							)
						)
					)
				)
            )
        );

        return $result;
    }
	
	public function getAllAdminMailAdresses() {
        $model = ClassRegistry::init('Publisher');
        $result = $model->find('all', array(
                'recursive' => 1,
				'conditions' => array(
					'OR' => array(
						'AND' => array(
							array('Publisher.email !=' => ''),
							array('Publisher.role_id =' => 2)
						),
						'OR' => array(
							array(
								'AND' => array(
									array('Publisher.email !=' => ''),
									array('Publisher.role_id =' => 4)
								)
							)
						)
					)
				)
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
    
    public function getDataprotectionPublisherCount($congregationId) {
    	$model = ClassRegistry::init('Publisher');
    
    	$result = $model->find('count', array(
    			'conditions' => array(
    					'congregation_id' => $congregationId,
    					'dataprotection' => 1
    			),
    			'recursive' => -1
    	)
    			);
    
    	return $result;
    }
    
    public function getPublisherCount($congregationId) {
    	$model = ClassRegistry::init('Publisher');
    
    	$result = $model->find('count', array(
    			'conditions' => array(
    					'congregation_id' => $congregationId
    			),
    			'recursive' => -1
    	)
    			);
    
    	return $result;
    }
    
    public function getAllDataprotectionUserCount() {
    	$model = ClassRegistry::init('Publisher');
    
    	$result = $model->find('count', array(
    			'conditions' => array(
    					'dataprotection' => 1
    			),
    			'recursive' => -1
    		)
    	);
    
    	return $result;
    }
    
    public function getAllUserCount() {
    	$model = ClassRegistry::init('Publisher');
    
    	$result = $model->find('count', array(
    			'recursive' => -1
    		)
    	);
    
    	return $result;
    }
    
    public function getDataPublisherWithoutLogin($congregationId) {
    	$model = ClassRegistry::init('Publisher');
    
    	$result = $model->find('all', array(
    			'conditions' => array(
    					'congregation_id' => $congregationId,
    					'dataprotection' => 0,
    					'email' => ""
    			),
    			'recursive' => -1
    	)
    			);
    
    	return $result;
    }
	
	public function getContactPersons($publisher) {
		$model = ClassRegistry::init('Publisher');

		$result = $model->find('all', array(
                'fields' => array('Publisher.id', 'Publisher.prename', 'Publisher.surname', 'Publisher.phone', 'Publisher.email', 'Publisher.description'),
                'recursive' => -1,
                'conditions' => array(
                    'Publisher.congregation_id' => $publisher['Congregation']['id'],
                    'Publisher.role_id =' => '4'
                )
            )
        );

		if(strpos($publisher['Congregation']['name'], "Solingen") !== false) {
			$result = $model->find('all', array(
					'fields' => array('Publisher.id', 'Publisher.prename', 'Publisher.surname', 'Publisher.phone', 'Publisher.email', 'Publisher.description'),
					'recursive' => -1,
					'conditions' => array(
						'OR' => array(
							'AND' => array(
								array('Publisher.congregation_id' => $publisher['Congregation']['id']),
								array('Publisher.surname =' => 'Ankenbrand'),
								array('Publisher.role_id =' => '2')
							),
							'OR' => array(
								array(
									'AND' => array(
										array('Publisher.congregation_id' => $publisher['Congregation']['id']),
										array('Publisher.role_id =' => '4')
									)
								)
							)
						)
					)
				)
			);
		}

        return $result;
	}
}
