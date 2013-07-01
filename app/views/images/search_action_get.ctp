<div style="padding: 20px;">

	<span style="font-size: 24px">Search result for: </span> 
	<? foreach($keyword as $index => $word) : ?>
		<span style="color: red;font-size:24px"><?= $word?></span>
		<? if ($index != count($keyword) - 1) : ?>
			<?= ',' ?>
		<? endif; ?>	
	<? endforeach; ?>
	<br/>
	
	<div class="serach_result">
		<? foreach ($images as $index=>$image) : ?>
			<? $class = ($index % 2) ? "search_result_even" : "search_result_odd";?>
			<div id=<?= 'image_' . $image[IMAGE][ID]?> class=<?= $class?>>
				<? $imgSrc = "/files/" . $image[ALBUM][Album::UserId] . "/Thumbnail/" . $image[IMAGE][ID] . '.' . $image[IMAGE][Image::Type] ; ?>
				<? $imageClass = ('portrait' == $image[IMAGE][Image::Orientation]) ? 'search_result_pictutre_portrait' :  'search_result_pictutre_landscape' ;?>
				<a href="/images/full_image/<?= $image[ALBUM][Album::UserId] . '/' . $image[IMAGE][ID]?>">
					<img class=<?= $imageClass?> src= <?= $imgSrc?>>
				</a>
				<div class="search_result_image_detail">
					<span style="font-size: 20px;"><?= $image[IMAGE][Image::Name];?></span> <br/>
					<a style="text-decoration: none; color: black;" href="/users/view/<?= $image[ALBUM][Album::UserId];?>">
						 by <span style="color:#0259C4;"><?= $image[IMAGE][Image::Photographer];?></span>
					</a><br/>
					<? if(!empty($image[IMAGE][Image::Description])) : ?>
						<?= $image[IMAGE][Image::Description]; ?><br/>
					<? endif; ?>
					
					View <?= $image[IMAGE][Image::Views];?> times <br/>
					<? if(!empty($image[EXIF][Exif::DateTimeOriginal])) : ?>
						Taken on <?= niceShort($image[EXIF][Exif::DateTimeOriginal]); ?>
					<? endif;?>
					
					<? if(!empty($image[TAG])) : ?>
					Tagged with 
						<? foreach($image[TAG] as $index => $tag) : ?>
							<span style="color:#0259C4;"><?= $tag[Tag::Title]; ?></span>
							<? if($index != count($image[TAG]) - 1) : ?>
								<?= ', ' ?>
							<? endif; ?>
						<? endforeach; ?>
					<? endif; ?>
				</div>
			</div>
		<? endforeach; ?>
	</div>
	<div style="margin-top: 10px">
		<?= $html->div('previous_linlk', $paginator->prev('<< Previous', array('class' => 'PrevPg'), null, array('class' => 'PrevPg DisabledPgLk'))) ?>
		<div class="paginatot_numbers"><?= $paginator->numbers() ?></div>
		<?= $html->div('next_linlk', $paginator->next('Next >>')) ?>
	</div>
</div>