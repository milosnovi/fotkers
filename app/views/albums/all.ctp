<?= $javascript->link(array('createGallery')); ?>
<?= $this->element('user_info', array('viewData' => $user[USER]))?>
<? $sessionData = $session->read();?>

<div id="formAddNewGallery" style="height: 40px; width: 800px; float: left;">

	<div id="createAlbumButton" style="width: 150px; float: left;">
		<a id="buttonCreateNewGallery" class="linkCreateAlbum">Create Gallery</a>
	</div>
	
	<div id="addGalleryForm" style="width: 600px; float: left; position: relative; display: none">
	<?= $ajax->form('Album', 
						   'post', 
							array(
								'id' => 'ajax_form',
								'model' => ALBUM,
								'url' => '/albums/addGallery',
								'update' => 'allAlbums'
							)
 	);?>
		<?= $form->input(Album::T_Name, array('div' => array('style' => 'margin-right: 10px; float: left'),'type' => 'text', 'label'=>''));?>
		<?= $form->input(Album::T_UserId, array('type' => 'hidden', 'value' => $sessionData[USER][ID]));?>
		<?= $form->end('submit');?>
		<span style="color: red; float: left; padding: 3px 10px 0px 10px;">OR</span>
		<a id="buttonCloseNewGallery" class="linkCreateAlbum">Close</a>
	</div>
	
</div>

<div id="allAlbums">

	<div id="poruke_o_greskama">
		<? if ($session->check('Message.flash')):
			$session->flash();
			endif;
		?>
	</div>
	<div id="allAlbums">
		<? if (isset($albums) && (0 < count($albums))) : ?>
			<? foreach ($albums as $album) : ?>
				<div id="albumThums_<?= $album[ALBUM][ID]?>" class="albumThums">
					<? if (0 != $album[ALBUM]['cover_id']) : ?>
						<a href = "/albums/view/<?= $album[ALBUM][ID] ?>" />
							<img style="width: 100px; margin: 10px; border: none;" src ="http://fotkers.comuf.com/files/<?= $album[ALBUM][Album::UserId];?>/Thumbnail/<?= $album['cover_picture'][ID]. '.' .$album['cover_picture'][Image::Type]?>" />
						</a>
					<? elseif (0 >= count($album[IMAGE])) : ?>
						<?= $html->image('imageGallery.png', array('style' => 'margin: 10px;'));?>
					<? else : ?>
						<? $randomIndex = $album['random_image']; ?>
						<a href = "/albums/view/<?= $album[ALBUM][ID] ?>" />
							<img style="width: 130px; margin: 10px; border: none;" src ="http://fotkers.comuf.com/files/<?= $album[ALBUM][Album::UserId];?>/Thumbnail/<?= $album[IMAGE][$randomIndex][ID]. '.' .$album[IMAGE][$randomIndex][Image::Type]?>" />
						</a>
					<? endif ; ?>
					
					<br/>
					<span  style="color: #0259C4; font-size: 18px;"><b><?= $album[ALBUM][Album::Name]; ?></b></span><br/>
					<span style="color: #777777;"><?= count($album[IMAGE])?> images </span><br/>
					<span>
						<?= $html->link('edit', array('controller' => 'albums', 'action' => 'editAlbum', $album[ALBUM][ID])); ?> |
						<?= $ajax->link('delete',
										array('controller' => 'albums', 'action' => 'deleteAlbum', $album[ALBUM][ID]),
    									array('update' => 'allAlbums'));
    					?>
					</span>
				</div>
			<? endforeach; ?>
		<? endif ; ?>
	</div>
</div>









