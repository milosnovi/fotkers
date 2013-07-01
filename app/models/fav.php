<?php 
	class Fav extends AppModel {
		const Id = 'Fav.id';
		
		const UserId = 'user_id';
		const ImageId = 'image_id';
		
		const T_UserId = 'Fav.user_id';
		const T_ImageId = 'Fav.image_id';
		
		var $belongsTo = array(
			IMAGE => array(
				'className' => IMAGE,
				'foreignKey' => Fav::ImageId
			),
			USER => array(
				'className' => USER,
				'foreignKey' => Fav::UserId
		));
	}

?>