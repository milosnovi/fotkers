<script type="text/javascript">
	$(document).ready(function() {
	
		$('.deleteAlbumLink').click(function() {
			var albumId = $(this).parent().find('#albumId').attr('value');
			$.ajax({
				type: "POST",
	            url: "/albums/delete_album/" + albumId,
	            dataType: 'json',
	            success: function(repsonse) {
            		if (repsonse.data.success) {
            			$("#albumThumb_"+repsonse.data.albumId).animate({opacity: "hide"}, 1000);
            		}
            	}
			})
		});
	});
	
	function funkcija(proba) {
		$("#picture_"+proba).animate({ opacity: "hide" }, 1000);
	};
</script>

<? 
	$url = "http://fotkers.comuf.com/files/" . $author[ID] . "/Thumbnail/";
	$user = $session->read(); // $session variable
	$albumsExist = (!empty($albums)) ? true : false ;
?>
<?=$this->element('user_info', array('viewData' => $author));?>

<div id="information">
	<? if ($session->check('Message.flash')):
		$session->flash();
		endif;
	?>
</div>

<div id="centralPanel">
	<div id="panelForPictures" class= <?= $albumsExist ? 'panelForPicturesWithAlbum' :  'panelForPicturesWithoutAlbum' ;?>>
	<? if (0 < count($images)) : ?>
		<? foreach($images as $image): ?>
			<? if ($albumsExist) : ?>
				<?  if ('landscape' == $image[IMAGE][Image::Orientation]) : ?>
					<? $orientationClass = 'width: 250px;';?>
				<? else : ?>
					<? $orientationClass = 'height: 240px;';?>
				<? endif ; ?>
			<? else : ?>
				<?  if ('landscape' == $image[IMAGE][Image::Orientation]) : ?>
					<? $orientationClass = 'width: 230px;';?>
				<? else : ?>
					<? $orientationClass = 'height: 240px;';?>
				<? endif;?>
			<? endif;?>
			<div id="picture_<?=$image[IMAGE][ID]?>" class= <?=$albumsExist ? 'picturePanel' :  'picturePanelWithoutAlbum' ;?> >
				<div class="pictureThumbnail" style ="padding-bottom: 5px;">
				   	 <a href="http://fotkers.comuf.com/images/full_image/<?=$author[ID] . '/' . $image[IMAGE][ID]?>">
				   	 	<img style="border: none; <?=$orientationClass;?>" src = "<?=$url . $image[IMAGE][ID] . '.' . $image[IMAGE][Image::Type]?>" />
				   	 </a>
		   	 	</div>
			   	 
			   	 <div class="pictureDetail">
			   	 	<b><span style="font-size: 15px;"><?=$image[IMAGE][Image::Name]?></span></b><br/>
			   	 	uploaded on <span style = "color:#3886E6"><?=$image[IMAGE]['created']; ?></span> 
			   	 	| <?=$ajax->link('Delete',
	    					array('controller' => 'images', 'action' => 'delete_image', $image[IMAGE][ID]),
	    					array('update' => 'information', 'complete' => "funkcija({$image[IMAGE][ID]});"),
	    					'Are you sure you want to delete this photo?'
	    				);?>
	    			<br/>
			   	 	<span><?=$image[IMAGE]['views'] ?> views / <?=count($image[COMMENT]); ?> comments</span>
			   	 </div>
		   	 </div>
		<? endforeach; ?>
	<? else : ?>
		<h2>
			<span style="color: cyan;"> Photostream is empty!</span>
		</h2>
	<? endif ; ?>
	</div>


	<div id="PanelForAlbums" style="display: <?=$albumsExist ? 'block' : 'none'?> ">
		<? foreach ($albums as $album) : ?>
			<div id="albumThumb_<?=$album[ID]?>" class="albumThumb">
				<?=$form->input('albumId', array('value' => $album[ID], 'type' => 'hidden'));?>
				<? if (0 != $album[Album::CoverId]) : ?>
					<?=$html->image("http://fotkers.comuf.com/files/".$author[ID]."/Thumbnail/" .$album[Album::CoverId].".jpg",
						array(
							'title' => $album[Album::Name],
							'style' => 'width: 100px; border: none',
							'url' => array(
								'controller' => 'albums',
								'action' => 'view', $album[ID])
							)
						);?><br/>
				<? elseif (!isset($album['random_index'])) : ?>
					<?=$html->image('imageGallery.png',
						array(
							'title' => $album[Album::Name],
							'style' => 'width: 100px; border: none',
							/*, 'url' => array(
							 * 		'controller' => 'albums',
							 * 		'action' => 'view', $album[ID])*/
						)); ?><br/>
				<? else : ?>
					<?=$html->image("http://fotkers.comuf.com/files/" . $author[ID] . "/Thumbnail/" . $album['random_index'][ID] . "." . $album['random_index'][Image::Type],
						array( 
							"title" => $album[Album::Name],
							'style' => 'width: 100px; border: none',
							'url' => array(
								'controller' => 'albums',
								'action' => 'view', $album[ID])
							)
						);?><br/>
				<? endif ; ?>
				<span style=""font-size: 14px;><?=$album[Album::Name]?></span><br/>
				<?=$html->link('edit',
							array('controller' => 'albums', 'action' => 'editAlbum', $album[ID]),
							array('style' => 'text-decoration:none; color: #0259C4;')
				);?> |
				<span class='deleteAlbumLink' style="color: #0259C4; text-decoration: none; cursor: pointer;">delete</span>
			</div>
		<? endforeach ; ?>
	</div>
</div>

<? if (isset($user[USER]) && ($user[USER][User::Username] == $author[User::Username])):?> 
	<?
	    echo $form->create(IMAGE, array('action' => 'action_upload_picture', 'type' => 'file'));
	    echo $form->file('File');
	    echo $form->submit('Upload');
	    echo $form->end();
	?>
<? endif;?>






