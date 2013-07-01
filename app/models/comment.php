<?php
class Comment extends AppModel {
	const Id = 'comment.id';
	
	const Value = 'value';
	const ImageId = 'image_id';
	const UserId = 'user_id';
	
	const T_Value = 'Comment.value';
	const T_ImageId = 'Comment.image_id';
	const T_UserId = 'Comment.user_id';
	
	var $belongsTo = array(USER, IMAGE);
	
}
?>
