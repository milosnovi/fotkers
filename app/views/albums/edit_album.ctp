<?= $javascript->link('chooseCoverPicture'); ?>
<?  $sessionData = $session->read();?>
<?= $this->element('user_info', array('viewData' => $author[USER]))?>

<div id="information">
	<? if ($session->check('Message.flash')):
		$session->flash();
		endif;
	?>
</div>

<div class="albumGeneralInformation">
	<h3>Cover picture:</h3>
	<div id="divCoverPicture" class="full_size">
		<? if ((0 == $album[ALBUM][Album::CoverId]) && (0 < count($album[IMAGE]))) :?>
			<img id="cover_picture" style="width: 200px;" src=<?= "http://fotkers.comuf.com/files/" . $album[ALBUM][Album::UserId] . "/Original/" . $randomCoverPicture[ID] ."." . $randomCoverPicture[Image::Type];?>>
		<? elseif (0 >= count($album[IMAGE])) : ?>
			<img id="cover_picture" style="width: 200px;" src="http://fotkers.comuf.com/img/album_cover.jpg">
		<? else : ?>
			<img id="cover_picture" style="width: 200px;" src=<?= "http://fotkers.comuf.com/files/" . $album[ALBUM][Album::UserId] . "/Original/" . $album[ALBUM][Album::CoverId] .".jpg";?>>
		<? endif;?>
	</div>
	
<!--
	<?= $ajax->form(ALBUM, 'post',array(
										'model' => ALBUM,
										'id' => 'milos',
										'update' => 'xxx',
								 		'url' => array(
				 							'controller' => 'albums',
				 							'action' => 'edit_album'
	 		))
 	);?>
-->
	
	<?= $form->create('Album', array('action' => 'editAlbum')); ?>
	<div class="albumInformations">
		<?= $form->input(Album::Id, array('value' => $album[ALBUM][ID]));?>
		<p><?= $form->input(Album::T_Name, array('label' => array('class' => 'labelForm'), 'style' => 'width: 300px','value' => $album[ALBUM][Album::Name]));?></p>
		<p><?= $form->input(Album::T_Photographer, array('label' => array('class' => 'labelForm'), 'style' => 'width: 300px', 'value' => $album[ALBUM][Album::Photographer]));?></p>
		<p><?= $form->input(Album::T_Description, array('label' => array('class' => 'labelForm'),'rows' => '7', 'cols' => '36', 'value' => $album[ALBUM][Album::Description]));?></p>
	</div>
</div>

<div class="addPhotoToAlbum" style="display: block; width: 800px;">

	<ul class="tabNavigation">
  		<li><a href="#AlbumImages"> Choose cover picture </a></li>
    	<li><a href="#allUncategorizedPicture"> Add your photos to album</a></li>
 	</ul>
 	
	<div id="AlbumImages" style="width: 800px; float:left; min-height: 200px; border: 1px solid;">
		<? if (0 < count($album[IMAGE])) : ?>
			<? foreach($album[IMAGE] as $image):?>
				<div id="photo" style="float: left; height: 120px; width: 120px; position: relative;">
					<img id="chooseCover.<?= $image[ID]?>.<?= $author[USER][ID]?>" style="position: absolute; top: 25px; left:25px; padding: 5px;"
					 	 src= <?= "http://fotkers.comuf.com/files/" . $author[USER][ID] . "/Thumbnail/" . $image[ID] . "_square.jpg"?> />
				</div>
			<? endforeach ; ?>
		<? else : ?>
			This album doesn't have any image yet!
		<? endif ; ?>
	</div>
	
	
	<div id = "allUncategorizedPicture" style="width: 800px; float:left; border: 1px solid;">
	<? $images = array(); ?>
		<? foreach($defaultAlbum[IMAGE] as $index => $image):?>
			<div style="float: left; height: 120px; width: 120px; position: relative;">
				<img style="position: absolute; top: 25px; left:25px; width: 65px; height: 65px; padding: 5px;"
				 	 src= <?= "http://fotkers.comuf.com/files/" . $author[USER][ID] . "/Thumbnail/" . $image[ID] . "_square.jpg"?> />
				 <!--<?= $form->input(/*"Image.{$image[ID]}"*/'Image',  array('div'=> array('id' => 'xxx', 'style' => 'float: left; position: absolute; left: 95px; top: 77px;'), 'label' => '', 'type' => 'checkbox'));?>
				-->
				<input type="checkbox" style="float: left; position: absolute; left: 95px; top: 77px;" name="data[Image][]" value=<?= $image[ID];?>>
			
			
			</div>
		<? endforeach;?>		
	</div>
</div>
	<?= $form->input(Album::T_CoverId, array('id' => 'albumCoverIdentifier' ,'type' => 'hidden', 'value' => $album[ALBUM][Album::CoverId]));?>
	<?= $form->input(Album::T_UserId, array('type' => 'hidden', 'value' => $sessionData[USER][ID]));?>
	<?= $form->end('Save changes');?>

