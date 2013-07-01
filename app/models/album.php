<?php
class Album extends AppModel {
	var $name = 'Album';
	
	const Id = 'Album.id';
	
	const Name = 'name';
	const Description = 'description';
	const UserId = 'user_id';
	const CoverId = 'cover_id';
	const Photographer = 'photographer';
	
	
	const T_Name = 'Album.name';
	const T_Description = 'Album.description';
	const T_UserId = 'Album.user_id';
	const T_CoverId = 'Album.cover_id';
	const T_Photographer = 'Album.photographer';
	
	const A_CoverPicture = 'cover_picture';
	const A_AllAlbumId = 'all_album_id';
	
	const A_T_CoverPicture = 'Album.cover_picture';
	const A_T_AllAlbumId = 'Album.all_album_id';
	
	var $belongsTo = array(
	 	USER,
		self::A_CoverPicture => array(
			'className' => IMAGE,
			'foreignKey' => self::CoverId
	));
	
	var $hasMany = array(
		IMAGE => array(
			'className' => IMAGE,
			'order' => 'Image.created ASC'
	));
}

?>
