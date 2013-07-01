<?php

class CommentsController extends AppController {
	
	var $helpers = array('Ajax');
	
	function addComment() {
		$sessionData = $this->Session->read();
		if (isset($sessionData[USER])) {
			if ($this->Comment->save($this->data[COMMENT])) {
				$comments = $this->Comment->find('all', array(
					'conditions' => array(Comment::T_ImageId => $this->data[COMMENT][Comment::ImageId]),
					'order' => 'Comment.created ASC'
				));
//				$this->set('comments', $comments);
				foreach($comments as &$comment) {
					$comment[COMMENT]['created'] = niceShort($comment[COMMENT]['created']);
				}
				$this->set(compact('comments'));
				$this->render('add_success','ajax');
			} else {
				$this->render('add_failure','ajax');
			}
		}
	}
	
	function deleteComment($commentId, $imageId) {
		if ($this->Comment->del($commentId)) {
			$comments = $this->Comment->find('all', array(
				'conditions' => array(Comment::T_ImageId => $imageId),
				'order' => 'Comment.created ASC'
			));
//			$this->set(compact('comments'));
//			$this->render('add_success', 'ajax');
		}
		exit(0);
	}
	
	function most_popular_image($userId) {
		$modelImage = new Image();
		$allUserImages = $modelImage->find('all', array('conditions' => array(Album::UserId => $userId)));
		$allUserImages = Set::extract($allUserImages, '{n}.' . IMAGE . '.' . ID);
		$imagesByComments = $this->Comment->find('all', array(
			'conditions' => array(Image::Id => $allUserImages),
			'fields' => array('COUNT('. Comment::ImageId .')', Image::Id, Image::T_Name, Image::T_Views, Image::T_Type, Image::T_Description),
			'order' => array('COUNT(Comment.image_id) DESC'),
			'group' => Comment::ImageId
		)); 
		
		$this->set('images', $imagesByComments);
		$modelUser = new User();
		$this->set('author', $modelUser->find('first', array(
			'conditions' => array(
				User::id => $userId
			)
		)));
	}
}
 
?>