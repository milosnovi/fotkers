<? foreach ($albums as $album) : ?>
	<div id="albumThums_<?= $album[ALBUM][ID]?>" class="albumThums">
	
		<? if (0 != $album[ALBUM]['cover_id']) : ?>
			<a href = "/albums/view/<?= $album[ALBUM][ID] ?>" />
				<img style="width: 100px;" src ="http://fotkers.comuf.com/files/<?= $album[ALBUM][Album::UserId];?>/Thumbnail/<?= $album['cover_picture'][ID]. '.' .$album['cover_picture'][Image::Type]?>" />
			</a>
		<? elseif (0 >= count($album[IMAGE])) : ?>
			<?= $html->image('imageGallery.png');?>
		<? else : ?>
			<? $randomIndex = $album['random_image']; ?>
			<a href = "/albums/view/<?= $album[ALBUM][ID] ?>" />
				<img style="width: 130px;" src ="http://fotkers.comuf.com/files/<?= $album[ALBUM][Album::UserId];?>/Thumbnail/<?= $album[IMAGE][$randomIndex][ID]. '.' .$album[IMAGE][$randomIndex][Image::Type]?>" />
			</a>
		<? endif ; ?>
		
		<br/>
		<span  style="color: #0259C4;"><b><?= $album[ALBUM][Album::Name]; ?></b></span><br/>
		<span style="color: #777777;"><?= count($album[IMAGE])?> images </span><br/>
		<span>
			<?= $html->link('Edit', 
									array('controller' => 'albums', 'action' => 'editAlbum', $album[ALBUM][ID])
			); ?>
			<?= $ajax->link('delete',
							array('controller' => 'albums', 'action' => 'deleteAlbum', $album[ALBUM][ID]),
    						array('update' => 'allAlbums'));
    		?>
		</span>
	</div>
<? endforeach;?>