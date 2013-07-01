<?php
	$author = $session->read(USER);
	$base_url = "http://fotkers.comuf.com/files/" . $author[ID] . "/Original/";
?>

<?php echo $this->element('user_info', array('viewData' => $author))?>
<h2 style="color: red"> Last uploaded files</h2>
<?php echo $form->create(IMAGE, array('url' => array('controller' => 'images', 'action' => 'show_upload_result'), 'id' => 'form_edit_uploaded_images')) ; ?>
	
	<div id="commonOperations" style='float: left'>
		<div id="addTagsToAllPhotos" class="addTagsToAllPhotos">
			<b>Add Tags</b><br/>
			<?php echo $form->input(Tag::T_A_AllTags, array(
				'class' => 'input_field',
				'label' => array('label' => 'Image name', 'class' => 'label'),
				'div' => array('id'=> 'input_div')
			));?>
			<?php ?>
		</div>
		
		<div id="addImagesToset" class="addTagsToAllPhotos" >
		<?php $allAlbums = $session->read(ALBUM);?>
			<b>Add to set:</b><br/>
				<?php echo $form->select(Album::A_T_AllAlbumId, $comboboxAlbum, null, array('class' => 'comboboxSelectAlbum'), 'Add all uploaded images to album'); ?>
		</div>
	</div>
		
	<div class="uploaded_images">
		<?php foreach ($images as $image) : ?>
			<?php $showImageClass = ('portrait' == $image[IMAGE][Image::Orientation]) ? 'portrait' : 'landscape' ; ?>
			<div id=<?php echo "uploaded_photo_".$image[IMAGE][ID];?> class="photo">
			
			<?php $imageName = "Image.{$image[IMAGE][ID]}.name"; ?>
			<?php $imageTags = "Image.{$image[IMAGE][ID]}.title"; ?>
			<?php $imageDescription = "Image.{$image[IMAGE][ID]}.description"; ?>
			 	<div class="image_wrapper" style="height: 270px;">
					<?php echo $html->image($base_url . $image[IMAGE][ID] . '.' . $image[IMAGE][Image::Type],
							array( "title" => $image[IMAGE][Image::Name], 'class' => $showImageClass, 'url' => array('controller' => 'albums', 'action' => 'view', $image[IMAGE][ID])));?><br/>
				</div>
				<?php echo $form->input($imageName, 
					array(
						'class' => 'input_field',
						'value' => $image[IMAGE][Image::Name],
						'label' => array('class' => 'label'),
						'div' => array('id'=> 'input_div')
					));?>
					
				<?php echo $form->input($imageDescription, 
					array(
						'type' => 'textarea',
						'class' => 'input_field_description',
						'label' => array('class' => 'label'),
						'rows' => '7',
						'cols' => '28',
						'div' => array('id'=> 'input_div')
					));?>
					
				<?php echo $form->input($imageTags,
					array(
						'class' => 'input_field',
						'label' => array('class' => 'label'),
						'div' => array('id'=> 'input_div')
					));?>
					
			</div>
			
		<?php endforeach ; ?>
	</div>
	<br/>
<?php echo $form->end('Save') ; ?>