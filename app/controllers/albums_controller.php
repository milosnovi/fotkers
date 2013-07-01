<?php
class AlbumsController extends AppController {
	var $uses = array(ALBUM, USER, IMAGE);
	
	
	function all($userId) {
		$sessionData = $this->Session->read();
		$this->Album->expects(ALBUM, 'cover_picture', IMAGE);
		$this->Album->Image->expects(IMAGE);
		
		$allAlbums = $this->Album->find('all', array(
			'conditions' => array(
				Album::UserId => $userId,
				'not' => array ( Album::T_Name => 'default')),
			'recursive' => 2
		));
		
		foreach($allAlbums as &$album) {
			if(0 == $album[ALBUM][Album::CoverId]) {
				if (0 < count($album[IMAGE])) {
					$randomCoverPicture = rand(0, count($album[IMAGE])-1); 
					$album['random_image'] = $randomCoverPicture; 
				}
			}
		}
		
		$this->set('user', $this->User->find('first', array(
									'conditions' => array(User::id => $userId),
									'recursive' => 0
								))
							);
		if (0 >= count($allAlbums)) {
			$this->Session->setFlash('There are not any album yet!');			
		} else {
			$this->set('albums', $allAlbums);
		}
	}
	
	function view($albumId) {
		$sessionData = $this->Session->read();
		if (isset($sessionData[USER])) {
//			$this->Image->expects(IMAGE, USER);
			$allAlbumData = $this->Album->find('first', array(
				'conditions' => array(
					Album::Id => $albumId
				)
			));
			
			if (0 == $allAlbumData[ALBUM][Album::CoverId]) {
				if (0 < count($allAlbumData[IMAGE])) {
					$randomImage = rand(0, count($allAlbumData[IMAGE])-1);
					$allAlbumData['random_image'] = $randomImage;
				}
			}
			$allAlbumData[ALBUM]['created'] = niceShort($allAlbumData[ALBUM]['created']);
			$authorData = $allAlbumData[USER];
			$this->set('author', $authorData);
			$this->set('albumData', $allAlbumData);
		} else {
			$this->redirect(array('controller' => 'users', 'action' => 'login'), true, null);
		}
		
	}
	
	function addGallery() {
		$sessionData = $this->Session->read();
		$albumName = $this->data[ALBUM][Album::Name];
		$userId = $this->data[ALBUM][Album::UserId];

		if ($this->Album->save($this->data[ALBUM])) {
			$lastInsertAlbumId =  $this->Album->getLastInsertId();
			
			$lastInsertedAlbum = $this->Album->find('first', array(
				'conditions' => array(
					Album::Id => $lastInsertAlbumId
				),
				'recursive' => 0,
				'fields' => array(Album::T_Name, Album::Id, Album::UserId, User::T_Username, 'cover_picture.name', 'cover_picture.id', 'cover_picture.type')
			));
			$allAlbumFromSession = $this->Session->read(ALBUM);
			$allAlbumFromSession[] = $lastInsertedAlbum;
			$this->Session->write(ALBUM, $allAlbumFromSession);
			$allAlbums = $this->Album->find('all', array(
				'conditions' => array(
						Album::UserId => $sessionData[USER][ID],
						'not' => array(Album::T_Name => 'default') 
				)
			));
			
			foreach($allAlbums as &$album) {
				if (0 == $album[ALBUM][Album::CoverId]) {
					if (0 < count($album[IMAGE])) {
						$randomImage = rand(0, count($album[IMAGE])-1);
						$album['random_image'] = $randomImage;
					}
				}
			}
			
			$this->set('albums', $allAlbums);
			$this->render('add_success', 'empty');	
		}
	}
	function delete_album($albumId) {
		$userSessionData = $this->Session->read(USER);
		
		$albumToDelete = $this->Album->find('count', array(
			'conditions' => array(
				Album::Id => $albumId,
				Album::UserId => $userSessionData[ID] 
			)
		));
		
		$success = true;
		if (1 == $albumToDelete) {
//			$success &= $this->Album->del($albumId);
		}
		$this->returnJsonData(array(
			'success' => true,
			'albumId' => $albumId
		));
	}
	
	function deleteAlbum($albumId) {
		$userSessionData = $this->Session->read(USER);
		if (!isset($albumId)) {
			$message = 'Invalid data supplied';
		}
		
		$albumToDelete = $this->Album->find('count', array(
			'conditions' => array(
				Album::Id => $albumId,
				Album::UserId => $userSessionData[ID] 
			)
		));
		
		$success = true;
		if (1 == $albumToDelete) {
			$success &= $this->Album->del($albumId);
		}
		
		$albums = $this->Album->find('all', array(
			'conditions' => array(
				Album::UserId => $userSessionData[ID],
				'not' => array ( Album::T_Name => 'default')),
			'recursive' => 2
		));
		
		foreach($albums as &$album) {
			if(0 == $album[ALBUM][Album::CoverId]) {
				if (0 < count($album[IMAGE])) {
					$randomCoverPicture = rand(0, count($album[IMAGE])-1); 
					$album['random_image'] = $randomCoverPicture; 
				}
			}
		}
		if ($success) {
			$this->set('albums', $albums);
			$this->render('add_success', 'ajax');
		}
	}
	
	function editAlbum($albumId = null) {
		$albumId = isset($this->data[ALBUM][ID]) ? $this->data[ALBUM][ID] : $albumId;
		$userSessionData = $this->Session->read(USER);
		
		if (isset($this->data)) {
			$this->Image->updateAll(
				array(Image::T_AlbumId => $albumId), 
				array(Image::Id => $this->data[IMAGE])
			);
			
			if ($this->Album->save($this->data)) {
				$this->redirect(array('controller' => 'albums', 'action' => 'view', $albumId), true, null);
			} else {
				$this->Session->setFlash("Changes doesn't save properly!");
			}
		}
		
		$album = $this->Album->find('first', array(
			'conditions' => array(
				Album::Id => $albumId,
				Album::T_UserId => $userSessionData[ID]
			)
		));
		$numberImages = count($album[IMAGE]);
		if (0 == $album[ALBUM][Album::CoverId] && (0 != $numberImages)) {
			$randomCoverPicture = rand(0, $numberImages - 1);
			$this->set('randomCoverPicture', $album[IMAGE][$randomCoverPicture]); 
		}
		
		$this->set('author', $this->User->find('first', array(
							'conditions' => array(User::id => $album[ALBUM][Album::UserId]),
							'recursive' => 0
						))
					);
		$defaultAlbum = $this->Album->find('first', array(
			'conditions' => array(
					Album::T_Name => 'default',
					Album::T_UserId => $userSessionData[ID] 
			)
		));

		$this->set('album', $album);
		$this->set('defaultAlbum', $defaultAlbum);
	}
	
	function add_image() {
		$albumId = isset($this->params['form']['albumId']) ? $this->params['form']['albumId'] : null;
		$userId = isset($this->params['form']['userId']) ? $this->params['form']['userId'] : null;
		$imageId = isset($this->params['form']['imageId']) ? $this->params['form']['imageId'] : null;
		
		if (!$albumId || !$userId || !$imageId) {
			$this->returnJsonData(array('success' => false, 'message' => 'Invalid data supplied'));
		}
		
		$image = $this->Image->find('first', array(
			'conditions' => array(
				Image::Id => $imageId,
				Album::T_UserId => $userId 
			)
		));
		
		if (!isset($image) || empty($image)) {
			$this->returnJsonData(array('success' => false));	
		}
		
		$dataToSave = array(Image::AlbumId => $albumId);
		$this->Image->id = $image[IMAGE][ID];
		if (!$this->Image->save($dataToSave)) {
			$this->returnJsonData(array('success' => false));	
		}
		$this->returnJsonData(array('success' => true));
	}
	
}
 
?>























