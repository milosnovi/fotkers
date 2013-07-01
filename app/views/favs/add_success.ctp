
<? if ($fav) : ?>
	<?= $ajax->link($html->image('fav.gif', array('id' => 'loader', 'style' => 'width: 23px; padding: 3px; border: none; margin-bottom: 2px;')), 
		'/favs/deleteFromFavorite/' . $imageId,
		array('update' => 'favoriteImage', 'style' => 'float: left;', 'complete' => "updateNumberOfFav('remove');"),
		null,
		false
	);?>
	<div id="actionAddToFav" style="float: left; padding-left:4px; padding-top:7px;">
		<span style="font-size: 11px; padding-bottom: 5px">a fav</span><br/>
	</div>
<? else : ?> 
	<?= $ajax->link($html->image('unfav.gif', array('id' => 'loader', 'style' => 'width: 23px; padding: 3px; border: none; margin-bottom: 2px;')),
		'/favs/addAsFavorite/' . $imageId,
		array('update' => 'favoriteImage', 'style' => 'float: left;', 'complete' => "updateNumberOfFav('add');"),
		null,
		false
	);?>
	<div id="actionAddToFav" style="float: left;">
		<span style="font-size: 11px; padding-left: 5px">add </span><br/>
		<span style="font-size: 11px;">to fav</span>
	</div>
<? endif; ?>