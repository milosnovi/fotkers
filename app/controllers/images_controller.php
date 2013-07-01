<?php 
	class ImagesController extends AppController {
		var $helpers = array('Paginator');
		var $uses = array(IMAGE, USER, ALBUM, EXIF, COMMENT, FAV, TAG);
		
		function beforeFilter() {
//			if (!$this->Session->read(USER)) {
//				$this->redirect(array('controller' => 'users', 'action' => 'login'), null, true);
//			}
			$loggedUser = $this->Session->read(USER);
			if (!$this->Session->read(ALBUM)) {
				$modelAlbum = new Album();
				$allAlbums = $modelAlbum->find('all', array(
					'conditions' => array(
						Album::UserId => $loggedUser[ID],
						'not' => array(Album::T_Name => 'default')
					),
					'recursive' => 0,
					'fields' => array(Album::T_Name, Album::Id, Album::UserId, User::T_Username, 'cover_picture.name', 'cover_picture.id', 'cover_picture.type')
				));
				foreach($allAlbums as &$album) {
					if (!isset($album['cover_picture'][ID])) {
						$album['cover_picture']['name'] = 'imageGallery';		
						$album['cover_picture']['type'] = 'png';		
					}
				}
				$this->Session->write(ALBUM, $allAlbums);
			}
		}
		
		function photostream ($username) {
			$userData = $this->User->find('first',
				array('conditions' => array(User::T_Username => $username),
				'recursive' => 0
			));
			
			$this->Image->expects(IMAGE, ALBUM, COMMENT);
			$images = $this->Image->find('all', array(
				'conditions' => array(
					Album::UserId => $userData[USER][ID]
				),
				'order' => 'Image.created DESC'
			));

			$albums = $this->Album->find('all', array(
				'conditions' => array(
					Album::UserId => $userData[USER][ID],
					'not' => array(Album::T_Name => 'default')
				),
				'order' => 'Album.created DESC',
				'recursive' => -1
			));
			
			foreach ($albums as &$album) {
				if (0 == $album[ALBUM][Album::CoverId]) {
					if (0 < count($album[IMAGE])) {
						$randomIndex = rand(0, count($album[IMAGE])-1);
						$randomCoverImage = array(
							ID => $album[IMAGE][$randomIndex][ID],
							Image::Type => $album[IMAGE][$randomIndex][Image::Type]
						);
						$album[ALBUM]['random_index'] = $randomCoverImage;
					}
				}
				$album = $album[ALBUM];
			}
			
			foreach($images as &$image) {
				$image[IMAGE]['created'] = niceShort($image[IMAGE]['created']);
			}
			
			$this->set('author', $userData[USER]);
			$this->set('albums', $albums);
			$this->set('images', $images);
		}
		
		function delete_image($imageId) {
			$user = $this->Session->read(USER);
			$image = $this->Image->find('first', array(
				'conditions' => array(
					Image::Id => $imageId,
					Album::UserId => $user[ID]
				))
			);
			$albums = $this->Album->find('all', array(
				'conditions' => array(
					Album::CoverId => $image[IMAGE][ID],
					Album::UserId => $user[ID]
				),
				'recursive' => -1
			));
			
			
			if ($albums) {
				// reset album cover_id
				foreach ($albums as $album) {
					$this->Album->id = $album[ALBUM][ID];
					$this->Album->save(array(Album::CoverId => 0));
				}
			}
			
			if (empty($image)){
				$this->set('message', 'Problem during deleting photo. Please try agian!');
				$this->render('delete_success','ajax');
			}
			
			$this->Image->id = $imageId;
			if (!$this->Image->delete($imageId)) {
				$this->set('message', 'Problem during deleting photo. Please try agian1!');
				$this->render('delete_success','ajax');
			} else {
				if (file_exists(WWW_ROOT . 'files/' . $user[ID] . '/Original/' . $image[IMAGE][ID] . '.' . $image[IMAGE][Image::Type])) {
					unlink(WWW_ROOT . 'files/' . $user[ID] . '/Original/' . $image[IMAGE][ID] . '.' . $image[IMAGE][Image::Type]);
				}
				exit();
			}
		}
		
		function most_popular_image($userId) {
			$this->Image->expects(IMAGE,ALBUM . '.' . Album::UserId);
			$images = $this->Image->find('all', array(
				'conditions' => array(Album::T_UserId => $userId),
				'fields' => array(Image::Id, Image::T_Name, Image::Type, Image::T_Views, Image::T_Description),
				'order' => array('Image.views DESC')
			));
			$this->set('images', $images);
			$this->set('author', $this->User->find('first', array(
				'conditions' => array(
					User::id => $userId
				)
			)));
		}
		
		function full_image($userId, $photoId) {
			$sessionData = $this->Session->read();
			if (!$photoId) {
				$this->Session->setFlash('Incorect id of photo');
			}
			
			$this->Image->bindModel(array(
		 		'hasOne' => array(
		 			FAV => array(
		 				'className' => FAV,
		 				'conditions' => array(
		 					'Fav.user_id' => $sessionData[USER][ID],
		 					'Fav.image_id' => $photoId
		 				))
		 			)
				)
			);
				
			$this->Image->expects(IMAGE, ALBUM, ALBUM . '.' . USER, COMMENT, EXIF);
			$this->Image->Comment->expects(USER);
			$this->Image->Exif->expects();
			$imageData = $this->Image->find('first', array(
				'conditions' => array(Image::Id => $photoId),
				'recursive' => 2
			));

			$increaseViews[IMAGE]['views'] = $imageData[IMAGE]['views'] + 1; 
			$this->Image->id = $imageData[IMAGE][ID];
			$this->Image->save($increaseViews);
			
			$allFavorites = $this->Fav->find('all', array(
				'conditions' => array(Fav::ImageId => $photoId),
				'recursive' => -1
			));
			$comments = $imageData[COMMENT];
			foreach($comments as &$comment) {
				$comment['created'] = niceShort($comment['created']);
			}
			
			$exif = $imageData[EXIF];
			if ($imageData) {
			 	$this->set('author', $imageData[ALBUM][USER]);
			 	$this->set('followingPhoto', $this->__findFollowPicture($userId, $photoId));
			 	$this->set('image', $imageData);
			 	$this->set('allFavorites', $allFavorites);
			 	$this->set('comments', $comments);
			 	$this->set('exif', $exif);
			 } else {
			 	$this->Session->setFlash('There is not photo which you want');
			 }
		}
		
		function home() {
			$userSessionData = $this->Session->read(USER);
			if (isset($userSessionData)) {
	     	    $this->Image->expects(array(IMAGE, ALBUM, ALBUM.'.'.USER));
				$images = $this->Image->find('all', array(
					'conditions' => array('not' => array(Album::UserId => $userSessionData[ID])),
					'order' => 'Image.created DESC',
					'limit' => 12,
					'recursive' => 2
				));
				$this->set('images', $images);
				$this->set('author', $this->User->find('first', array(
					'conditions' => array(User::id => $userSessionData[ID]),
					'recursive' => 0	
				)));
			} else {
				$this->redirect(array('controller' => 'users', 'action' => 'login'), null, true);
			}
		}
		
		private function __findFollowPicture($userId, $currentImage) {
			$this->Image->expects(IMAGE, ALBUM);
			$allImages = $this->Image->find('all', array(
				'conditions' => array(
					Album::T_UserId => $userId			
				),
				'order' => 'Image.created DESC'
			));
			$allImages = Set::extract($allImages, '{n}.' . IMAGE);
			$followingPhoto = array();
			$numberPicture = count($allImages);
			for ($i = 0; $i < $numberPicture; $i++) {
				if ($currentImage == $allImages[$i][ID]) {
					if ($i == ($numberPicture - 1)) {
						$followingPhoto['previous'][ID] =  $allImages[0][ID];
						$followingPhoto['previous'][Image::Type] =  $allImages[0][Image::Type];
					} else {
						$followingPhoto['previous'][ID] = $allImages[$i + 1][ID];
						$followingPhoto['previous'][Image::Type] = $allImages[$i + 1][Image::Type];
					}
					
					if ($i == 0) {
						$followingPhoto['next'][ID] = $allImages[$numberPicture - 1][ID];
						$followingPhoto['next'][Image::Type] = $allImages[$numberPicture - 1][Image::Type];
					} else {
						$followingPhoto['next'][ID] = $allImages[$i - 1][ID];
						$followingPhoto['next'][Image::Type] = $allImages[$i - 1][Image::Type];
					}
				}
			}
			return $followingPhoto;
		}

		function findNavigationImage() {
			$imageId = $this->params['form']['imageId'];
			$userId = $this->params['form']['userId'];
			$type = $this->params['form']['type'];
			
			$images = $this->Image->find('all', array(
				'conditions' => array(
					Album::UserId => $userId			
				),
				'order' => 'Image.created DESC'
			));
			
			$imagesIds = Set::extract($images, '{n}.' . Image::Id);
			$numberPicture = count($imagesIds);
			for ($i = 0; $i < $numberPicture; $i++) {
				if ($imageId == $imagesIds[$i]) {
					if ('previous' == $type) {
						$followingPhoto = $imagesIds[$i + 1];
					} else {
						$followingPhoto = $imagesIds[$i - 1];
					}
				}
			}
			$this->returnJsonData($followingPhoto);
		}
		
		function saveChanges() {
			$userId = $this->params['form']['userId'];
			$imageId = $this->params['form']['imageId'];
			$newName = isset($this->params['form']['name']) ? $this->params['form']['name'] : null;
			$newDescription = isset($this->params['form']['description']) ? $this->params['form']['description'] : null;
			
			$success = true;
			$sessionData = $this->Session->read();
			$loggedUser = $sessionData[USER];
			if ($loggedUser[ID] == $userId) {
				
				$imageToEdit = $this->Image->find('first', array(
					'conditions' => array(
						Image::Id => $imageId,
						Album::UserId => $userId
					),
					'fields' => array(Image::Id)
				));	
				
				$this->Image->id = $imageToEdit[IMAGE][ID];
				$dataToSave = $newName ? array(Image::Name => $newName) : array(Image::Description => $newDescription);
				if (!$this->Image->save($dataToSave)) {
					$message = "Image name is not save succesfully";
				}
			} else {
				$success = false;
				$message = "Permission deny! This isn't your photo";
			}
			
			$this->returnJsonData(array(
				'success' => $success,
				'message' =>  $success ? "Image succesfully saved" : $message
			));
		}
		
		function action_upload_avatar() {
 			$loggedUser = $this->Session->read(USER);
 			
 			$fileType = $this->data[IMAGE]['File'][Image::Type];
 			$fileName = $this->data[IMAGE]['File'][Image::Name];
 			$fileSize = $this->data[IMAGE]['File']['size'];
 			$fileError = $this->data[IMAGE]['File']['error'];
 			$fileTmp = $this->data[IMAGE]['File']['tmp_name'];
 
 			$imageDetatil = FileType::parseExtension($fileName);
 			$dataToSave[IMAGE][Image::Name] = $imageDetatil[0];
 			$dataToSave[IMAGE][Image::Type] = $imageDetatil[1];
 			$dataToSave[IMAGE][Image::UserId] = $loggedUser[ID];
 			
	 		if ((($fileType == "image/gif") || ($fileType == "image/jpeg") || ($fileType == "image/pjpeg")) && ($fileSize < 2000000000)) {
		  		if ($fileError > 0) {
			    	echo "Return Code: " . $fileError . "<br />";
		    	} else {
		 			// This Folder contain all picture which belongs to User
		 			$thumb = FileType::generateThumbnail($fileTmp, 130, 130);
					$target_path = WWW_ROOT . 'files/' . $loggedUser[ID] . "/" . 'avatar' . '.' . strtolower($dataToSave[IMAGE][Image::Type]);
															
					if(!(imagejpeg($thumb, $target_path, 100))) {
							imagedestroy($original);
							imagedestroy($thumb);
							throw new Exception ('Photo uploading failed');
					}
					imagedestroy($img);
					imagedestroy($thumb);
		    	}
	 		}
	 		
	 		$this->redirect(array('controller' => 'users', 'action' => 'edit_profile' , $loggedUser[ID]), null, true);
		}
		
		function show_upload_result() {
			$loggedUser = $this->Session->read(USER);
			if ($this->Session->read('TempUploadedFile')) {			
				if ($this->data) {
					foreach($this->data[IMAGE] as $index => $image) {
						if (!empty($this->data[ALBUM][Album::A_AllAlbumId])) {
							$image[Image::AlbumId] = $this->data[ALBUM][Album::A_AllAlbumId];
						}
						$this->Image->id = $index;
						$this->Image->save($image);
						
						$tags = $this->data[IMAGE][$index][Tag::Title];
						if (isset($this->data[TAG][Tag::A_AllTags])) {
							$tags = $tags . ',' . $this->data[TAG][Tag::A_AllTags];
						}
						$this->Tag->addTagsToImage($tags, $index, $loggedUser[ID]);
					}
					$this->Session->del('TempUploadedFile');
					$this->redirect(array('controller' => 'images', 'action' => 'photostream', $loggedUser[User::Username]), null, true);
				}
				
				$uploadedFiles = $this->Session->read('TempUploadedFile');
				$imagesIds = Set::extract($uploadedFiles, IMAGE);
				
				$uploadedImages = $this->Image->find('all', array(
					'conditions' => array(
						Image::Id => $imagesIds,
						Album::UserId => $loggedUser[ID] 
					)
				));
	//			if (count($uploadedImages) == count($imagesIds)) {
					$allAlbums = $this->Session->read(ALBUM);
					$comboboxAlbum = array();
					foreach ($allAlbums as $album) {
						$comboboxAlbum[$album[ALBUM][ID]] = $album[ALBUM][Album::Name];
					}
					$this->set('comboboxAlbum', $comboboxAlbum);				
					$this->set('images', $uploadedImages);				
	//			}
			} else {
				$this->redirect(array('controller' => 'images', 'action' => 'photostream', $loggedUser[User::Username]), null, true);
			}
		}
		
		
		function action_upload_picture() {
 			$loggedUser = $this->Session->read(USER);
 			$result = $this->__uploadImage($this->data[IMAGE]);
 			if (!$result['success']) {
 				$this->set('error_message', $result['message']);	
 			} else {
		  		$this->redirect(array('controller' => 'images', 'action' => 'photostream', $loggedUser[User::Username] ), null, true);
 			}
 		}
 		
		function upload_images() {
			$loggedUser = $this->Session->read(USER);
			if ($this->data) {
				$images = $this->data[IMAGE];
				$dataToReturn['success'] = true;
				foreach($images as $image) {
					$result = $this->__uploadImage($image);
					$dataToReturn[IMAGE][] = $result[IMAGE];
					$dataToReturn['success'] &= $result['success']; 
				}
				if (1 < count($images)) {
					$this->Session->write('TempUploadedFile', $dataToReturn);
					$this->redirect(array('controller' => 'images', 'action' => 'show_upload_result'), null, true);	
				} else {
					$this->redirect(array('controller' => 'images', 'action' => 'photostream', $loggedUser[User::Username]), null, true);
				}
				
			}
		}
		
		private function __uploadImage($image) {
			$loggedUser = $this->Session->read(USER);

			$fileType = $image['File'][Image::Type];
 			$fileName = $image['File'][Image::Name];
 			$fileSize = $image['File']['size'];
 			$fileError = $image['File']['error'];
 			$fileTmp = $image['File']['tmp_name'];
 			
			if ('image/jpeg' == $fileType) {
		        $original = imagecreatefromjpeg($fileTmp);
		    } else if ('image/gif' == $fileType) {
		        $original = imagecreatefromgif($fileTmp);
		    }
			$width = imagesx($original);
			$height = imagesy($original);

	        if ($width <= $height) {
 	        	$orientation = 'portrait';
 	        } else {
 	        	$orientation = 'landscape';
 	        }
 			
 			$imageDetatil = FileType::parseExtension($fileName);
 			$dataToSave[IMAGE][Image::Name] = $imageDetatil[0];
 			$dataToSave[IMAGE][Image::Type] = $imageDetatil[1];
 			$dataToSave[IMAGE][Image::Orientation] = $orientation;
 			
 			$main_folder_url = WWW_ROOT . 'files/' . $loggedUser[ID];
 			
 			$album = $this->Album->find('first', array(
 				'conditions' => array(
 					Album::T_Name => 'default',
 					Album::UserId => $loggedUser[ID]
 				),
 				'fields' => Album::Id,
 				'recursive' => -1
 			));
 			
 			if ($album) {
 				$albumId = $album[ALBUM][ID];
 			} else {
 				$this->Album->id = null;
 				$this->Album->save(array(Album::Name => 'default', Album::UserId => $loggedUser[ID], Album::Description => 'system'));
 				$albumId = $this->Album->getLastInsertId();
 			}
 			$dataToSave[IMAGE][Image::AlbumId] = $albumId;
 			$dataToSave[IMAGE][Image::Photographer] = $loggedUser[User::Username] . ' ' . $loggedUser[User::Lastname];
 			
	 		if ((($fileType == "image/gif") || ($fileType == "image/jpeg") ||($fileType == "image/pjpeg")) && ($fileSize < 2000000000000000)) {
		  		if ($fileError > 0) {
			    	echo "Return Code: " . $fileError . "<br />";
		    	} else {
		    		$this->Image->id = null;
		    		$success = $this->Image->save($dataToSave);
		 			if ($success) {
		 				$lastInsertImageID = $this->Image->getLastInsertId();
		 				
	 					$this->Exif->saveExifData($fileTmp, $lastInsertImageID);
			 			// This Folder contain all picture which belongs to User
			    		$original_folder_url = $main_folder_url. '/Original'; 
			    		$thumbnail_folder_url =  $main_folder_url . '/Thumbnail'; 
			    		
						// Create the folder if it does not exist
						if(!is_dir($original_folder_url) && !is_dir($thumbnail_folder_url)) {
//							mkdir($main_folder_url); // Creating folder
							mkdir($original_folder_url);
							mkdir($thumbnail_folder_url);
						}
 						$newOriginal = FileType::generateThumbnail($original, 800, 800, $width, $height);
     					imagejpeg($newOriginal, $original_folder_url . '/' . $lastInsertImageID . '.' . $imageDetatil[1], 100);
     					
					    $thumb = FileType::generateThumbnail($original, 200, 200);
					    $target_path = $thumbnail_folder_url . '/' . $lastInsertImageID . '.' . $imageDetatil[1];				
						if(!(imagejpeg($thumb, $target_path, 100))) {
							imagedestroy($thumb);
							throw new Exception ('Photo uploading failed');
						}
						
						$squareThumb = FileType::generateSquareThumb($original, $width, $height);
	 					$target_path_square =  $thumbnail_folder_url . '/' . $lastInsertImageID . '_square.' . $imageDetatil[1];
						
						if (!(imagejpeg($squareThumb, $target_path_square, 100))) {
							imagedestroy($squareThumb);
						}
						
						$result[IMAGE] = $lastInsertImageID;
						$result['success'] = true;
		 			}
		    	}
			} else {
			  	$result['message'] = "Invalid file";
			  	$result['success'] = false;
 				return $result;
		  	}
		  	return $result;
		}
		
 		function viewFullSize($userId, $imageId) {
 			$this->layout = 'fullSize';
 			$this->Image->expects(IMAGE, ALBUM);
 			$imageData = $this->Image->find('first', array(
 				'conditions' => array(
 					Image::Id => $imageId,
 					Album::UserId => $userId
 				),
 				'recursive' => 0
 			));
 			if ($imageData) {
	 			$this->set('imageData', $imageData);
 			}
 		}
 		
 		function search_action() {
			$searchTitle = $this->data[IMAGE]['search_title'];
 			$this->redirect("/images/search_action_get/{$searchTitle}"); 
 		}
 		
 		function search_action_get($searchTitles, $page = null) {
 			$this->paginate = array(
				IMAGE => array(
					'limit' => 5,
//					'page' => $page,
					'order' => array('Image.name' => 'asc')
			));
		
 			$searchTitle = explode(' ', $searchTitles);
 			$filter = array();
 			for($i = 0; $i < count($searchTitle); $i++) {
 				$word = trim($searchTitle[$i]);
 				$filter['or'][] = array('or' => array(
 						Image::T_Name . ' LIKE' =>  "%$word%",
 						Image::T_Photographer . ' LIKE' => "%$word%"
 					)
 				);	
 			}
 			
 			$search_result = $this->paginate(IMAGE, $filter);
 			foreach($search_result as &$image) {
 				foreach($image[TAG] as &$tag) {
 					$newTagData = array();
					$newTagData[ID] = $tag[ID]; 					
					$newTagData[Tag::Title] = $tag[Tag::Title];
					$tag = $newTagData;  					
 				}
 			}
 			$this->set('keyword', $searchTitle);
 			$this->set('images', $search_result);
 		}
 		
 		function email() {
			$to = 'm.novicevic@gmail.com';
			$subject = 'Test email';
			$message = "Hello World!\n\nThis is my first mail.";
			$headers = "From: webmaster@example.com\r\nReply-To: webmaster@example.com";
			$mail_sent = @mail( $to, $subject, $message, $headers );
			echo $mail_sent ? "Mail sent" : "Mail failed";
			exit();
 		}
 		
 		function delete_server_dir($dir = 'files/41/Thumbnail') {
         	// List the contents of the directory table 
         	$dir_content = scandir($dir); 
            // Is it a directory? 
           	if ($dir_content !== false) {
            //For each directory entry 
           		foreach ($dir_content as $entry) { 
                	// Unix symbolic shortcuts, we go 
					if(!in_array($entry, array ('.', '..'))) { 
						// We find the path from the beginning 
						$entry = $dir. '/'. $entry; 
                       // This entry is not an issue: it clears 
                 		if (!is_dir($entry)) { 
         					unlink($entry); 
						} else { 
							//This entry is a folder, it again on this issue 
  							rmdir_recursive ($entry); 
						} 
					} 
				}
			} 
			// It has erased all entries in the folder, we can now erase 
			rmdir ($dir); 
			exit();
 		}
	}
?>