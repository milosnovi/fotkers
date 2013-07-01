<?php 
class FavsController extends AppController {
	var $uses = array(FAV, IMAGE, ALBUM, USER);
	
	
	function addAsFavorite($imageId) {
		$sessionData = $this->Session->read();
		
		if (!isset($sessionData[USER])) {
			 $this->redirect(array('controller' => 'users', 'action' => 'login', true, null));
		}
		
		$isFavorite = $this->Fav->find('first', array(
			'conditions' => array(
				Fav::ImageId => $imageId,
				Fav::UserId => $sessionData[USER][ID]
			)
		));
//		if (!$isFavorite) {
			if ($this->Fav->save(array(Fav::ImageId => $imageId, Fav::UserId => $sessionData[USER][ID]))) {
				$this->set('fav', true);
				$this->set('imageId', $imageId);
				$this->render('add_success', 'ajax');	
//			} else {
//				$this->render('add_failure', 'ajax');
			}
//		}
	}
	
	function deleteFromFavorite($imageId) {
		if (!isset($imageId)) {
			echo 'sranje!!!!!';
		}
		
		$sessionData = $this->Session->read();
		if (!isset($sessionData[USER])) {
			 $this->redirect(array('controller' => 'users', 'action' => 'login', true, null));
		}
		
		$favorite = $this->Fav->find('first', array(
			'conditions' => array(
				Fav::ImageId => $imageId,
				Fav::UserId => $sessionData[USER][ID]
			)
		));
		
		if (!$favorite){
			echo 'this fav is already delted';
		}
		
//		$this->ch($favorite);
		$sucess = $this->Fav->del($favorite[FAV][ID]);
		if ($sucess) {
			$this->set('imageId', $imageId);
			$this->set('fav', false);
			$this->render('add_success', 'ajax');	
		} else {
			$this->render('add_failure', 'ajax');
		}
	}
	
	function view($userId) {
		$this->set('author', $this->User->find('first', array(
			'conditions' => array(
				User::id => $userId
			)
		)));
		
		$this->Fav->expects(FAV, IMAGE, IMAGE . '.' . ALBUM, USER);
		$this->Fav->User->expects();
		$allData = $this->Fav->find('all', array(
			'conditions' => array(
				Fav::UserId => $userId
			),
			'recursive' => 2
		));
		
		$this->set('allFavs', $allData);		
	}
	
	function most_popular_image($userId) {
		$allUserImages = $this->Image->find('all', array('conditions' => array(Album::UserId => $userId)));
		$allUserImages = Set::extract($allUserImages, '{n}.' . IMAGE . '.' . ID);
		$allImages = $this->Fav->find('all', array(
			'conditions' => array(Image::Id => $allUserImages),
			'fields' => array('COUNT(' . Fav::ImageId . ')', Image::Id, Image::T_Name, Image::T_Type,Image::T_Views, Image::T_Description),
			'order' => array('COUNT(Fav.image_id) DESC'),
			'group' => Fav::ImageId
		));
		
		$this->set('author', $this->User->find('first', array(
			'conditions' => array(
				User::id => $userId
			)
		)));
		$this->set('images', $allImages);
	}
}
?>



