<?php

class TagsController extends AppController {
	var $uses=array('ImagesTags', TAG, IMAGE);
	var $helpers = array('Ajax');
	
	function addTags() {
		$tagsIsEntered = false;
		if (isset($this->data[TAG][Tag::Title]) && !empty($this->data[TAG][Tag::Title])) {
			$tagsIsEntered = true;
			$this->Tag->addTagsToImage($this->data[TAG][Tag::Title], $this->data['image_id'], $this->data['user_id']);
		} else {
			$message = "Invalidate data suplied";
		}
		
		$this->ImagesTags->bindModel(array(
	 		'belongsTo' => array(
	 			TAG => array(
	 				'className' => TAG
	 				))
	 			)
		);
		
		$dataToReturn = $this->ImagesTags->find('all', array(
			'conditions' => array(
				'image_id' => $this->data['image_id']
			)
		));

		$this->set('tags', $dataToReturn);
		$this->set('imageId', $this->data['image_id']);
		
		if ($tagsIsEntered) {
			$this->render('add_success','ajax');
		} else {
			$this->set('message', $message);
			$this->render('failure', 'ajax');
		}
	}
	
	function deleteTag($id, $imageId) {
		$success = true;
		
		if (!$id) {
			$success = false;
			$message = 'Problem during deleting. Please try again!';				
		}
		
		if ($success) {
			$success = $this->ImagesTags->del($id);
		}
		
		$this->ImagesTags->bindModel(array(
	 		'belongsTo' => array(
	 			TAG => array(
	 				'className' => TAG
	 				))
	 			)
		);
		
		$dataToReturn = $this->ImagesTags->find('all', array(
			'conditions' => array(
				'image_id' => $imageId
			)
		));
		
//		$this->ch($dataToReturn);
		
		if ($success) {
			$this->set('tags', $dataToReturn);
			$this->set('imageId', $imageId);
			$this->render('add_success', 'ajax');	
		}
	}
}
 
?>