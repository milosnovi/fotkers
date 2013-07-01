<?php
	class User extends AppModel {
		var $name = 'User';
		
		const id = 'User.id';
		const Username = 'username';
		const Password = 'password';
		const Firstname = 'firstname';
		const Lastname = 'lastname';
		const Email = 'email';
		const Avatar = 'avatar';
		const AvatarType = 'avatar_type';
		const Country = 'country';
		const City = 'city';
		const Zanimanje = 'zanimanje';
		const Moto = 'moto';
		
		const T_Username = 'User.username';
		const T_Password = 'User.password';
		const T_Firstname = 'User.firstname';
		const T_Lastname = 'User.lastname';
		const T_Email = 'User.email';
		const T_Avatar = 'User.avatar';
		const T_AvatarType = 'User.avatar_type';
		const T_Country = 'User.country';
		const T_City = 'User.city';
		const T_Zanimanje = 'User.zanimanje';
		const T_Moto = 'User.moto';
		
		const A_RetypePassword = 'retype_password';
		const A_RemindMe = 'remind_me';
		
		const T_A_RemindMe = 'User.remind_me';
		
		var $hasMany = array(COMMENT,
			ALBUM => array(
				'className' => ALBUM
				/*'order' => 'Album.created ASC'*/
			));
		
	/*	var $validate = array(
			self::Firstname => array(
				'rule' => 'notEmpty',
				'required' => true,
				'message' => 'Firstname can not be empty!' 				
			),
			self::Username => array(
				'rule' => 'notEmpty',
				'required' => true,
				'message' => 'Firstname can not be empty!' 				
			), 
			self::Email => array(
				'rule' => 'notEmpty',
				'required' => true,
				'message' => 'Email can not be empty!'
			)
		);*/
		
	}
?>