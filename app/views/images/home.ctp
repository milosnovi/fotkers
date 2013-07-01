<?php $url = "http://fotkers.comuf.com/files/"; ?>
<?php echo $this->element('user_info', array('viewData' => $author[USER]))?>

<div id = "panelForPictures">
<?php if (0 < count($images)): ?>
	<?php foreach($images as $image): ?>
		<div class="picturePanelHomePage">
			<div class="pictureThumbnail">
			   	 <a href = "/images/full_image/<?php echo $image[ALBUM][USER][ID] . '/' .$image[IMAGE][ID]?>">
			   	 	<img style="width: 100px; border:none;" src = "<?php echo $url . $image[ALBUM][USER][ID] . '/Thumbnail/' . $image[IMAGE][ID] . '.' . $image[IMAGE][Image::Type]?>" />
			   	 </a>
	   	 	</div>
		   	 
		   	 <div class="pictureDetail">
		   	 	<?php echo $image[IMAGE][Image::Name]?><br/>
		   	 	by <?php echo $html->link($image[ALBUM][USER][User::Username], array('controller' => 'images', 'action' => 'photostream', $image[ALBUM][USER][User::Username]))?>
		   	 </div>
		   	 
	   	 </div>
	<?php endforeach; ?>
<?php else: ?>
	There are not wny images!
<?php endif;?>
</div>