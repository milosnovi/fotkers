<?php 
	class Image extends AppModel {
		var $name = 'Image';	
		
		const Id = 'Image.id';
		
		const Name = 'name';
		const Type = 'type';
		const AlbumId = 'album_id';
		const UserId = 'user_id';
		const Description = 'description';
		const Added = 'added';
		const Views = 'views';
		const Orientation = 'orientation';
		const Photographer = 'photographer';
		
		const T_Name = 'Image.name';
		const T_Type = 'Image.type';
		const T_AlbumId = 'Image.album_id';
		const T_UserId = 'Image.user_id';
		const T_Description = 'Image.description';
		const T_Views = 'Image.views';
		const T_Orientation = 'Image.orientation';
		const T_Photographer = 'Image.photographer';

		var $belongsTo = array(ALBUM);
		
		var $hasOne = array(EXIF);
		
		var $hasMany = array(
			COMMENT => array(
				'className'  => COMMENT,
				'foreignKey' => 'image_id',
				'order'		 => 'Comment.created ASC',
			)
		);
		
		var $hasAndBelongsToMany = TAG;
	}
	
	
?>