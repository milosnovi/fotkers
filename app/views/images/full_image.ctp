<? 
	$url = "http://fotkers.comuf.com/files/" . $author[ID] . "/Original/";
	$user = $session->read();
	$urlThumb = "http://fotkers.comuf.com/files/" . $author[ID] . "/Thumbnail";
	
	$nextImageId = $followingPhoto['next'][ID];
	$nextImageType = $followingPhoto['next'][Image::Type];
	 
	$previousImageId = $followingPhoto['previous'][ID];
	$previousImageType = $followingPhoto['previous'][Image::Type];
	
	$isOwnerImage = false;
	if (isset($user[USER]) && ($user[USER][ID] == $author[ID])) {
		$isOwnerImage = true;
	}
?>
<?= $this->element('user_info', array('viewData' => $author))?>

<div id="pictureCommentPanel">
	<div id="pictureTopMenu" style="height: 35px;">
	
		<? if (!$isOwnerImage) : ?>
			<div id = "favoriteImage" style ='float: left; width: 60px;'>
				<? if (empty($image[FAV])) : ?>
					<?= $ajax->link($html->image('unfav.gif', array('id' => 'loader', 'style' => 'width: 23px; padding: 3px; border: none; margin-bottom: 2px;')), 
												'/favs/addAsFavorite/' . $image[IMAGE][ID], array('update' => 'favoriteImage', 'style' => 'float: left;', 'complete' => "updateNumberOfFav('add');"), null, false);?>
					<div id="actionAddToFav" style="float: left;">
						<span style="font-size: 11px; padding-left: 5px">add </span><br/>
						<span style="font-size: 11px;">to fav</span>
					</div>
				<? else : ?>
					<?= $ajax->link($html->image('fav.gif', array('id' => 'loader', 'style' => 'width: 23px; padding: 3px; border: none; margin-bottom: 2px;')),
												'/favs/deleteFromFavorite/' . $image[IMAGE][ID], array('update' => 'favoriteImage', 'style' => 'float: left;','complete' => "updateNumberOfFav('remove');"), null, false);?>
					<div id="actionAddToFav" style="float: left; padding-left:4px; padding-top:7px;">
						<span style="font-size: 11px; padding-bottom: 5px">a fav</span><br/>
					</div>
				<? endif ; ?>
			</div>
		<? endif ; ?>
		
		<div id="allSizes" style="width: 50px; float: left;">
			<?= $html->link(($html->image('zoom.gif', array('id' => "zoom_picture", 'class' => 'zoom_icon'))), 
									array('controller' => 'images', 'action' => 'viewFullSize', $author[ID], $image[IMAGE][ID]),
									array('target' => '_blank'),
									null,
									false);?>
		</div>
		<? $allAlbums = $session->read(ALBUM);?>
		<? if($isOwnerImage) : ?>
			<? if(isset($allAlbums) && !empty($allAlbums)) : ?>
				<div id="actionAddToGallery" style="float: left; position: relative;" >
					<a class='dropDownMenu' href="#">
						<span style="font-size: 11px; padding-left: 2px">add to</span><br/>
						<span style="font-size: 11px;">gallery</span>
					</a>
					<ul class="subnav">
						<div style="font-size: 11px;">
							<div style="padding-top: 5px; padding-left: 5px;">choose a set below:</div>
							<hr style="margin-bottom:5px;">
							<? foreach($allAlbums as $album) : ?>
							    <li>
							    	<a>
							    		<? if (isset($album['cover_picture'][ID])) : ?>
								    		<?= $html->image("{$urlThumb}/{$album['cover_picture'][ID]}.{$album['cover_picture']['type']}", array('style'=>'width: 30px;')) ; ?>
								    		<span><?= $album[ALBUM][Album::Name]?></span>
								    		<input type="hidden" value=<?= $album[ALBUM][ID]; ?>>
							    		<? else :?>
							    			<?= $html->image("{$album['cover_picture']['name']}.{$album['cover_picture']['type']}", array('style'=>'width: 30px;')) ; ?>
							    			<span><?= $album[ALBUM][Album::Name]?></span>
								    		<input type="hidden" value=<?= $album[ALBUM][ID]; ?>>
							    		<? endif ; ?>
							    	</a>
							    </li>
						    <? endforeach ; ?>
					    </div>
					</ul>
				</div>
			<? endif ; ?>
		<? endif ; ?>
	</div>
	
	<div id="PanelForLargePicture" style="width: 500px; float: left;">
		<div id="FullPictureSize" class = "full_size">
			<? $fullImageClass = ('portrait' == $image[IMAGE][Image::Orientation]) ? 'full_size_view_portrait': 'full_size_view_landscape';?>
			<img class=<?= $fullImageClass;?> src="<?= $url . '/' . $image[IMAGE][ID] . '.' . $image[IMAGE][Image::Type]?>">
		</div>
	</div>
	
	<div id="commentsPanel">
		<? foreach($comments as $comment):?>
			<div id="comment_<?= $comment[ID];?>" class="comment-block">
				<div class="comment-owner">
					<img style="width: 80px;" src="<?= "http://fotkers.comuf.com/files/" . $comment[Comment::UserId] . '/avatar.jpg';?>"> 
				</div>
				<div class="comment-content">
					<h4 style="margin:0px;"><?= $html->link($comment[USER][User::Username], array('controller' => 'images', 'action' => 'photostream', $comment[USER][User::Username]))?> says:</h4>
					<p style= "margin-top: 5px; margin-left: 10px; margin-bottom: 5px;"><?= $comment[Comment::Value];?><br/></p>
					<span  style="font-size: 11px; display: block; margin-top: 8px'">
						<span> posted <?= $comment['created'];?></span>
						(<span> <?= $ajax->link('delete', 
													'/comments/deleteComment/'. $comment[ID] . '/' . $comment[Comment::ImageId], 
						 							array(/*'update' => 'commentsPanel', */'complete' => "updateNumberOfComments('remove',{$comment[ID]});")
					 							);?>
 						</span> |
						 <span> <?= $html->link('edit', array('controller' => 'images', 'action' => 'photostream', $comment[USER][User::Username])); ?></span>)
					</span>
				</div>
			</div>
		<? endforeach;?>
	</div>
	
	<?= $ajax->form(array('type' => 'post',
						'options' => array(
							'id' => 'addComment',
							'style' => 'float: left',
							'update' => 'commentsPanel',
							'complete' => "updateNumberOfComments('add');",
					 		'url' => array(
 								'controller' => 'comments',
 								'action' => 'addComment'
 		))
 	)
 	);?>
 		<? //echo ($html->image('star.gif', array('id' => 'loader'))); ?>
		<span style="color: red; font-size: 24px;">Add your comment</span>
		<?= $form->input(Comment::T_Value, array('type' => 'textarea', 'label'=>''));?>
		<?= $form->input(Comment::T_UserId, array('type' => 'hidden', 'value' => $user[USER][ID]));?>
		<?= $form->input(Comment::T_ImageId, array('type' => 'hidden', 'value' => $image[IMAGE][ID]));?>
	<?= $form->end('Add Comment');?>
</div>

<div id="navigationPanel">
	
	<div id="leftRightNavigation">
		<div id= 'previousPicture'>
			<a href = "/images/full_image/<?= $author[ID] . '/' .$previousImageId?>">
				<img id="IMGprevious" style="border: 0px; width: 90px; position: absolute; top: 0px; left: 0px;" src="<?= $urlThumb . '/' . $previousImageId . '_square' . '.' . $previousImageType?>">
			</a>
			<input id="previous_image_id" type="hidden" value="<?= $previousImageId;?>">
			<span id="previous_image" style="color: silver; cursor: pointer; left:22px; position:absolute; top:94px;"> previous </span>
		</div>
		
		<div id= 'nextPicture' >
			<a href = "/images/full_image/<?= $author[ID] . '/' . $nextImageId?>">
				<img id="IMGnext" style=" border: 0px; width: 90px; position: absolute; top: 0px; left: 0px;" src="<?= $urlThumb . '/' . $nextImageId . '_square' . '.' . $nextImageType;?>">
			</a>
			<input id="next_image_id" type="hidden" value="<?= $nextImageId;?>">
			<span id="next_image" style="color: silver; cursor: pointer; left:39px; position:absolute; top:94px;"> next</span>
		</div>
		
	</div>
	
	<div id="imageInfo">
		<fieldset>
		<legend> Image info</legend>
			<p><div style="height: 20px">
				<label id="labelNameImage" class="labelImageInfo" style="padding-top:2px">Name:</label> 
				<div id="imageName" title="click to edit" style="float:left; width:125px; padding: 3px 5px 3px 1px;"><?= $image[IMAGE][Image::Name];?></div>
			</div></p>
			<? if ('default' != $image[ALBUM][Album::Name]) : ?>
				<p><div id="albumInfo">
					<label id="labelNameImage" class="labelImageInfo" style="padding-top:2px">Album:</label> 
					<div id="imageName" title="click to edit" style="float:left; width:125px; padding: 3px 5px 3px 1px;"><?= $image[ALBUM][Album::Name];?></div>
				</div></p>
				<br/>
			<? endif ; ?>
			
			<div id="imageDescription" style="height: 20px" title="click to edit description">
				<p><label id="labeldescriptionImage" class="labelImageInfo" style="padding-top:2px" >Description: </label></p>
			</div>
			
			<div id="imageDescriptionValue" style="float:left; width: 230px; padding: 10px 5px 3px 1px; text-align: justify;"><?= $image[IMAGE][Image::Description];?></div>
		</fieldset>
		<br/>
		<fieldset>
			<legend> Exif </legend>
			<p><label class="labelImageInfo">Camera:</label><?= $exif[Exif::Make] . $exif[Exif::Model]?><br/></p>
			<p><label class="labelImageInfo">Exposure:</label><?= $exif[Exif::ExposureTime]?><br/></p>
			<p><label class="labelImageInfo">Aperture:</label><?= $exif[Exif::Aperture]?><br/></p>
			<p><label class="labelImageInfo">Focal Length:</label><?= $exif[Exif::FNumber]?><br/>
			<p><label class="labelImageInfo">ISO:</label><?= $exif[Exif::Iso]?></p>
		</fieldset>
		<br/>
		<fieldset>
			<legend> Statistics</legend>
			<p style="margin-left: 20px"> Viewed <?= $image[IMAGE][Image::Views]?> times </p>
			<p style="margin-left: 20px"> <span id='favNumber'><?= count($allFavorites)?></span> people call this as favorite </p>
			<p style="margin-left: 20px"> <span id='commentNumber'><?= count($comments)?></span>&nbsp comments </p>
			
		</fieldset>
		<br/>
		<div id="tags">
			<fieldset>
				<legend> Tags</legend>
				<ul>
				<? if (0 < count($image[TAG])) : ?>
						<? foreach ($image[TAG] as $tag) : ?>
							<li class="tags">
								<?= $tag[Tag::Title]; ?>
								<?= $ajax->link('[x]', array('controller' => 'tags', 'action' => 'deleteTag', $tag['ImagesTag'][ID], $tag['ImagesTag']['image_id']),
															  array('update' => 'tags')); ?>
							</li> 
						<? endforeach ; ?>
				<? else : ?>
					There are no image tags.
				<? endif ; ?>
				</ul>
				<? if ($isOwnerImage) : ?>
					<div style="margin-bottom: 10px;">
						<a id='addTagsLink' class='linkToButton'> Add Tags</a>
					</div>
				
					<div id="formAddTags" style="display: none;">
						<?= $ajax->form(array('type' => 'post',
									'options' => array(
										'id' => 'addTagsForm',	
										'update' => 'tags',
								 		'url' => array(
			 								'controller' => 'tags',
			 								'action' => 'addTags'
						 		))
						 	)
					 	);?>
					 	<?= $form->input(Tag::T_Title, array('label' => '', 'style' => 'width: 200px;'));?>
						<?= $form->input('user_id', array('type' => 'hidden', 'value' => $user[USER][ID]));?>
						<?= $form->input('image_id', array('type' => 'hidden', 'value' => $image[IMAGE][ID]));?>
						<?= $form->end('Add');?>
						OR
						<a id="buttonCloseNewTags" class='linkToButton'>Cancel</a>
					</div>
				<? endif ; ?>
			</fieldset>
		</div>
		
	</div>
</div>