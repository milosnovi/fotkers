<?php 
 	echo $this->element('user_info', array('viewData' => $author[USER])) ; 
	echo $this->element('most_popular_image', array('images' => $images, 'author' => $author, 'type' => COMMENT));
?>