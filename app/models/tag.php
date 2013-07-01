<?php
	App::import('Model', 'ImagesTags');


	class Tag extends AppModel {
		var $name = 'Tag';

		const Id = 'Tag.Id';
		
		const Title = 'title';
		const UserId = 'user_id';
		
		const T_Title = 'Tag.title';
		const T_UserId = 'Tag.user_id';
		
		const A_AllTags = 'all_tags';
		
		const T_A_AllTags = 'Tag.all_tags';
		
		var $hasAndBelongsToMany = IMAGE;
		
		function addTagsToImage($tags, $imageId, $userId) {
			$allTags = explode(",", $tags);
			//Create array of tags with no duplicate and no empty string
			$finallTagsArray = array();
			foreach($allTags as $tag) {
				if ('' != $tag) {
					$finallTagsArray[] = trim($tag);
				}
			}
			
			$finallTagsArray = array_unique($finallTagsArray);
			// Find all tag titles which belongsTo user
			$existingTags = $this->find('all', array(
				'conditions' => array(
					Tag::UserId => $userId
				)
			));
			$existingTags = Set::extract($existingTags, '{n}.' . Tag::T_Title);
			// save all tags which doesn not exist in database
			$tagsAddToDataBase = array_diff($finallTagsArray, $existingTags);
			if (!empty($tagsAddToDataBase)) {
				$dataToSave = array();
				foreach ($tagsAddToDataBase as $index => $tag) {
					$dataToSave[TAG][$index] = array(
						Tag::Title => $tag,
						Tag::UserId => $userId
					);
				}
				$this->saveAll($dataToSave[TAG]);
			}
			// update join table images_tags
			$allTags = $this->find('all', array(
				'conditions' => array(
					Tag::Title => $finallTagsArray
				),
				'fields' => array(Tag::Id)
			));
			$allTags = Set::extract($allTags, '{n}.' . Tag::Id);
			
			$modelImageTags = new ImagesTags();
			$tagsAlreadyAppendToImage = $modelImageTags->find('all', array(
				'conditions' => array(
					'image_id' => $imageId
				)
			));
			$tagsAlreadyAppendToImage = Set::extract($tagsAlreadyAppendToImage, '{n}.' . 'ImagesTags.tag_id');
			$tagsToInsert = array_diff($allTags, $tagsAlreadyAppendToImage);
			
			foreach ($tagsToInsert as $tag) {
				$modelImageTags->id = null;
				$dataToSave = array('ImagesTags' => array(
					'image_id' => $imageId,
					'tag_id' => $tag
				));	
				$modelImageTags->save($dataToSave);
			}
			
		}
	}

?>