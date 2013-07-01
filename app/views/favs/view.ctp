<? $url="http://fotkers.comuf.com/files/" ?>

<?= $this->element('user_info', array('viewData' => $author[USER])); ?>
<div class="allFavoritesPhotos" >
	<span style="padding-bottom: 20px;">Here are some of <?= $author[USER][User::Username]?>'s favorites from other members </span>
	<div class="vsThumbs">
		<? foreach($allFavs as $fav) : ?>
			<a href="http://fotkers.comuf.com/images/full_image/<?= $fav[IMAGE][ALBUM][Album::UserId]?>/<?= $fav[IMAGE][ID]?>">
				<img style="border: none;" src = "<?= $url . $fav[IMAGE][ALBUM][Album::UserId] . "/Thumbnail/" . $fav[FAV][Fav::ImageId] . '_square.jpg'?>" >
			</a>
		<? endforeach; ?>
	</div>
</div>