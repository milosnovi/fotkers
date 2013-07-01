<?
	$sessionData = $session->read(USER); 
	$url = "http://fotkers.comuf.com/files/" . $user[USER][ID];
?>

<?= $this->element('user_info', array('viewData' => $user[USER]))?>
<div style="float: left; width: 100%; font-size: 16px;">

	<div class="userDetailSubDetail">
	
		<div class="showAvatarPicture">
			<div class="avatarPicture">
				<img src = "<?= $url . '/avatar.jpg';?> "/>
				<span style="display: block; padding-top: 10px;">
					<span style="color:#0063DC"><?= $user[USER][User::Username]?></span>'s profile picture
				</span>		
			</div>
		</div>
		
		<div class = "viewUserDetail">
			<? if(!empty($user[USER][User::Username])) : ?>
				<p>
					<label class="labelForm">Username:</label>
					<td><?= $user[USER][User::Username]?></td>
				</p>
			<? endif; ?>
			
			<? if(!empty($user[USER][User::Firstname])) : ?>
				<p>
					<label class="labelForm">Firstname:</label>
					<td><?= $user[USER][User::Firstname]?></td>
				</p>
			<? endif; ?>
			
			<? if(!empty($user[USER][User::Lastname])) : ?>
				<p>
					<label class="labelForm">Lastname:</label>
					<td><?= $user[USER][User::Lastname]?></td>
				</p>
			<? endif; ?>
			
			<? if(!empty($user[USER][User::Email])) : ?>
				<p>
					<label class="labelForm">Email:</label>
					<td><?= $user[USER][User::Email]?></td>
				</p>
			<? endif; ?>
			
			<? if(!empty($user[USER][User::City])) : ?>
				<p>
					<label class="labelForm">City:</label>
					<td><?= $user[USER][User::City]?></td>
				</p>
			<? endif; ?>
			
			<? if(!empty($user[USER][User::Country])) : ?>
				<p>
					<label class="labelForm">Country:</label>
					<td><?= $user[USER][User::Country]?></td>
				</p>
			<? endif; ?>
			
			<? if(!empty($user[USER][User::Zanimanje])) : ?>
				<p>
					<label class="labelForm">Zanimanje:</label>
					<td><?= $user[USER][User::Zanimanje]?></t>
				</p>
			<? endif; ?>
			
			<? if(!empty($user[USER][User::Moto])) : ?>
				<p>
					<label class="labelForm">Moto:</label>
					<td><?= $user[USER][User::Moto]?></t>
				</p>
			<? endif; ?>
			<br/>
			
			<? if($sessionData[ID] == $user[USER][ID]) : ?>
				<?= $html->link('Edit user account',
					array('controller' => 'users', 'action' => 'edit_profile', $user[USER][ID]),
					array('class' => 'link_view_photostream'));?>
			<? endif; ?>
		</div>
	</div>
	
	<div class="userDetailSubDetail">
		<p>&nbsp&nbsp&nbsp LAST  5 ADDED PHOTOS</p>
		<? foreach($images as $image) : ?>
			<a href="/images/full_image/<?= $user[USER][ID]?>/<?= $image[IMAGE][ID]?>" >
				<img style="padding: 0 10px 10px 10px; border: none;" src ="<?= $url . "/Thumbnail/" . $image[IMAGE][ID] . '_square.' . $image[IMAGE][Image::Type];?>"></img>
			</a>
		<? endforeach; ?>
		<?= $html->link('View photostream',
			array('controller'=>'images', 'action'=> 'photostream', $user[USER][User::Username]),
			array('class' => 'link_view_photostream'));?>
	</div>
	
	<div class="userDetailSubDetail">
		<p>&nbsp&nbsp&nbsp 5 TOP RATED PHOTOS</p>
		<? foreach($imagesByView as $image) : ?>
			<a href="/images/full_image/<?= $user[USER][ID]?>/<?= $image[IMAGE][ID]?>" >
				<img style="padding: 0 10px 10px 10px; border: none;" src ="<?= $url . "/Thumbnail/" . $image[IMAGE][ID] . '_square.' . $image[IMAGE][Image::Type];?>"></img>
			</a>
		<? endforeach; ?>
		<?= $html->link('View statistics',
			array('controller'=>'favs', 'action'=> 'most_popular_image', $user[USER][ID]),
			array('class' => 'link_view_photostream'));?>
	</div>
	
</div>