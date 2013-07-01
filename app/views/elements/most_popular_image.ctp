<?php 
	$url = "http://fotkers.comuf.com/files/" . $author[USER][ID] . "/Thumbnail/";
?>

<div style="float:left;font-size:19px;width:600px; border-bottom: 3px solid #F1F1F1; margin-top: 15px;">
	<div style="float:left; width:70px; font-size: 16px; color: #1057AE">Sort by</div>
	<ul class="sortByPopular">
	  <li class="<?php echo (FAV == $type) ? 'liTagsPopularActive': 'liTagsPopular'?>"> <?php echo $html->link('Favs', array('controller' => 'favs', 'action' => 'most_popular_image', $author[USER][ID]))?></li>
	  <li class="<?php echo (COMMENT == $type) ? 'liTagsPopularActive' : 'liTagsPopular'?>"> <?php echo $html->link('Comments', array('controller' => 'comments', 'action' => 'most_popular_image', $author[USER][ID]))?> </li>
	  <li class="<?php echo (IMAGE == $type) ? 'liTagsPopularActive' : 'liTagsPopular'?>"> <?php echo $html->link('Views', array('controller' => 'images', 'action' => 'most_popular_image', $author[USER][ID]))?></li>
	</ul>
</div>

<div class="popularImagesPanel">
	<?php foreach ($images as $index => $image) : ?>
		<div class="popularImages">
			<div style="padding-right: 20px; width: 75px; float: left;">
				<a style="text-decoration: none;" href = "/images/full_image/<?php echo $author[USER][ID] . '/' . $image[IMAGE][ID] ;?>">
					<img style="border: none;" src = "<?php echo $url . $image[IMAGE][ID] . '_square.' . $image[IMAGE][Image::Type]?>">
				</a>
			</div>
			<div style="width: 400px; float: left;">
			 	<p style="margin-top: 0px"><b>#<?php echo $index + 1;?></b>:&nbsp <span style="color: #0063DC;">
			 		<?php echo $html->link($image[IMAGE][Image::Name],
			 								array('controller' => 'images', 'action' => 'full_image', $author[USER][ID], $image[IMAGE][ID]),
			 								array('style' => 'color:#0063DC; font-weight:bold; text-decoration:none;')
 					);?>
		 		</span></p>
				<?php if (isset($image[IMAGE][Image::Description]) && !empty($image[IMAGE][Image::Description])) : ?>
					• description: <?php echo  $image[IMAGE][Image::Description]; ?><br/>
				<?php endif ; ?>
				
				 <?php if (FAV == $type) : ?>
					• <?php echo  $image[0]['COUNT(image_id)'] ?> people count this as favorite / 
				<?php elseif (COMMENT == $type) : ?>
					• <?php echo  $image[0]['COUNT(image_id)'] ?> comments / 
				<?php endif ; ?>
				
				View <?php echo $image[IMAGE][Image::Views]?> times 
			</div>
		</div>
	<?php endforeach ; ?>
</div>
