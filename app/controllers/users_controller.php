<?php
	class UsersController extends AppController {
		var $name = 'Users';
		var $uses = array(USER, ALBUM);
		var $helpers = array('Html', 'Form', 'Time');
		var $components = array('Cookie');
		
		var $paginate = array(
			USER => array(
				'limit' => 4,
				'order' => "User.username ASC"
				)
			);
			
		function create() {
			if (!empty($this->data)) {
				$username = $this->data[USER][User::Username];
				$password = $this->data[USER][User::Password];
				$password2 = $this->data[USER][User::A_RetypePassword];
				
				// if all field are filled properly
				foreach ($this->data[USER] as $key => $value) {
					if (!isset($key) || $value == '') {
						echo ('You have not filled the form out correctly');
					}
				}
		
				if ($password != $password2) {
					echo 'The password you entered do not match. Try again';
				}
		
				if ((strlen($password) < 3) || (strlen ($password) > 16)) {
					echo ('Your password must be between 6 and 16 characters. Try again');
				}
		
				$usernameIsTaken = $this->User->find('all', array(
					'conditions' => array(User::Username => $username)
				));
				
				if ($usernameIsTaken) {
					echo 'Username is taken = choose another one.';
				}

				$this->User->id = null;
				$success = $this->User->save($this->data);
				ch($success);
				if ($success) { // New user is successfully created
					$lastInsertID = $this->User->getLastInsertId();
					$usersFolderUrl = WWW_ROOT . "/files/" . $lastInsertID;
					mkdir($usersFolderUrl); 
//					mkdir($usersFolderUrl . '/defaultAlbum/'); 
					if (!copy(WWW_ROOT  . "/img/man.jpg", $usersFolderUrl . '/avatar.jpg' )){
						echo 'Problem during copying a default user picture';
					}
					$this->data[USER][ID] = $lastInsertID;
					$this->Session->write(USER, $this->data[USER]);
					// create system album
	 				$defaultAlbumToSave['name'] = 'default';
	 				$defaultAlbumToSave['user_id'] = $lastInsertID;
	 				$defaultAlbumToSave['description'] = 'system';
	 				$this->Album->save($defaultAlbumToSave);
	 				
					$this->redirect(array('controller' => 'images','action' => 'photostream', $username), true, null);
				}
			}
		}
		
		function login() {
			if (!$this->Session->read(USER)) {
				if ($this->Cookie->read(User::T_Username) && $this->Cookie->read(User::T_Password)) {
					$this->Session->write(USER);
					$user = $this->User->find('first', array(
						'conditions' => array(
							User::T_Username => $this->Cookie->read(User::T_Username),
							User::T_Password => $this->Cookie->read(User::T_Password)
						),
						'recursive' => -1
					));
					$this->Session->write(USER, $user[USER]);
					$this->redirect(array('controller' => 'images', 'action' => 'photostream', $user[USER][User::Username]), true, null);
				}
						
				if ($this->Session->valid()) {
					if (!empty($this->data)) {
						$userValidation = $this->User->find('count', array(
							'conditions' => array(User::T_Username => $this->data[USER][User::Username])
						));
						if (1 < $userValidation) {
							$this->Session->setFlash('You can not log in. Please try again!');
							$this->redirect(array('action' => 'login'), true, null);
						} else if (1 == $userValidation) {
							$passwordValidation = $this->User->find('count', array(
								'conditions' => array(
									User::T_Username => $this->data[USER][User::Username],
									User::T_Password => $this->data[USER][User::Password]
									),
								'recursive' => 0
							));
							if (1 == $passwordValidation) {
								$loggedUser = $this->User->find('first', array(
									'conditions' => array(
										User::T_Username => $this->data[USER][User::Username],
										User::T_Password => $this->data[USER][User::Password]
										)
									));
									
								$this->Session->write(USER, $loggedUser[USER]);
								if ($this->data[USER][User::A_RemindMe]) {
									$this->Cookie->write(User::T_Username, $loggedUser[USER][User::Username], false, '1 hour');
									$this->Cookie->write(User::T_Password, $loggedUser[USER][User::Password], false, '1 hour');
								}
								
								$this->redirect(array('controller' => 'images', 'action' => 'photostream', $this->data[USER][User::Username]),true, null);
							} else {
								$this->Session->setFlash('Your password is not correct. Retype password!');
								$this->redirect(array('action' => 'login'), true, null);
							}
						} else {
							$this->Session->setFlash('Username does not exist!');
							$this->redirect(array('action' => 'login'), true, null);
						}
					}
				}
			} else {
				$userData = $this->Session->read(USER);
				$this->redirect(array('controller' => 'images', 'action' => 'photostream', $userData[User::Username]), true, null);
			}
		}
		
		function logout() {
			if ($this->Session->valid()) {
				$this->Session->destroy();
			}
			
			if ($this->Cookie->read(User::T_Username) && $this->Cookie->read(User::T_Password)) {
				$this->Cookie->del(User);
			}
			
			$this->redirect('/');
		}
		
		function view($id) {
//			ch($this->referer());
			$sessionData = $this->Session->read();
			if (!$id) {
				$this->Session->setFlash('Invalid user identification');
				$this->redirect(array('action' => 'view', $id), true, null);
			}
			
			$user = $this->User->find('first',
				array('conditions' => array(User::id => $id),
				'recursive' => -1
			));
			
			$modelImage = new Image();
			$modelImage->expects(IMAGE, ALBUM);
			$images = $modelImage->find('all', array(
				'conditions' => array(
					Album::UserId => $user[USER][ID]
				),
				'limit' => 5,
				'order' => 'Image.created DESC'
			));
			
			$modelTopRatedImage = new Image();
			$modelTopRatedImage->expects(IMAGE, ALBUM);
			$imagesByView = $modelTopRatedImage->find('all', array(
				'conditions' => array(
					Album::UserId => $user[USER][ID]
				),
				'limit' => 5,
				'order' => 'Image.views DESC'
			));
			
			if ($user) {
				$this->set('user', $user);
				$this->set('images', $images);
				$this->set('imagesByView', $imagesByView);
			}
		}
		
		function all_user() {
			$data = $this->paginate(USER);
			$this->set('users', $data);
			
			$userSessionData = $this->Session->read(USER);
			if (!isset($userSessionData)) {
				$this->redirect(array('action' => 'login'), true, null);
			}
			
//			$this->set('allUsers', $this->User->find('all'));
		}
		
		function edit_profile($userId) {
			$loggedUser = $this->Session->read(USER);
			$inputData = $this->data;
			if (isset($loggedUser) && ($userId == $loggedUser[ID])) {
				if (!isset($this->data)) {
					$user = $this->User->find('first', array(
						'conditions' => array(
							User::id => $loggedUser[ID]
						),
						'recursive' => -1
					));
					$this->set('user', $user);
				} else {
					if ($this->User->save($this->data)) {
						$this->Session->setFlash('User data is sucessfuly saved');
						$this->redirect(array('action' => "edit_profile/{$userId}"));						
					} else {
						$user = $this->User->find('first', array(
							'conditions' => array(
								User::id => $loggedUser[ID]
							),
							'recursive' => -1
						));
						$this->set('user', $user);
						$this->Session->setFlash('User data is not sucessfuly saved');
					}
				}
			} else {
				$this->redirect(array('action'=>'login'), null, true);
			}
		}
	}
?>