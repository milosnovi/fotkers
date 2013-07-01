<? 
	$url = "http://fotkers.comuf.com/files/" . $author[ID] . "/Thumbnail/";
	$user = $session->read(); // $session variable
?>
<?= $this->element('user_info', array('viewData' => $author))?>

<div class="coverThumb">
	<? if (0 != $albumData[ALBUM]['cover_id']) : ?>
		<img style="width:280px" src="<?= 'http://fotkers.comuf.com/files/' . $author[ID] . '/Original/' . $albumData[Album::A_CoverPicture][ID].'.'.$albumData[Album::A_CoverPicture][Image::Type]?>">
	<? else : ?>
		<? $randomIndex = $albumData['random_image']; ?>
			<img style="width: 280px;" src ="http://fotkers.comuf.com/files/<?= $albumData[ALBUM][Album::UserId];?>/Thumbnail/<?= $albumData[IMAGE][$randomIndex][ID]. '.' .$albumData[IMAGE][$randomIndex][Image::Type]?>" />
	<? endif ; ?>
	
</div>

<div class="vsThumbs">
<? foreach($albumData[IMAGE] as $image):?>
	<a style="text-decoration: none;" href = "/images/full_image/<?= $author[ID] . '/' . $image[ID]?>">
		<img style="width: 75px; border: none;" src = "<?= $url . $image[ID] . '_square.' . $image[Image::Type]?>">
	</a>
<? endforeach;?>
</div>
<div class="albumDetail">
	<div class="albumDetailView">
		<label class="labelAlbumInfo" style="padding-top:2px">Album name:</label> 
		<div style="float:left; width:125px; padding: 3px 5px 3px 1px;"><?= $albumData[ALBUM][Album::Name];?></div>
	</div>
	
	<div class="albumDetailView">
		<label class="labelAlbumInfo" style="padding-top:2px">Photographer:</label> 
		<div  style="float:left; width:125px; padding: 3px 5px 3px 1px;"><?= $albumData[ALBUM][Album::Photographer];?></div>
	</div>
	
	<div class="albumDetailView">
		<label class="labelAlbumInfo" style="padding-top:2px">Description:</label> 
		<div style="float:left; width:250px; padding: 3px 5px 3px 1px;"><?= $albumData[ALBUM][Album::Description];?></div>
	</div>
	
	<div class="albumDetailView">
		<label class="labelAlbumInfo" style="padding-top:2px">Created:</label> 
		<div style="float:left; width:250px; padding: 3px 5px 3px 1px;"><?= $albumData[ALBUM]['created'];?></div>
	</div>
	
	<div class="albumDetailView">
		<?= $html->link('edit album properties', array('controller' => 'albums', 'action' => 'editAlbum', $albumData[ALBUM][ID])); ?>
	</div>
</div>

<pre>
<? //print_r ($albumData);?>
</pre>