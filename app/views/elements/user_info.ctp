<? $user = $session->read(USER);?>

<div id = "photostream" style = "padding-left: 5px; padding-bottom: 20px; padding-top: 20px; height: 50px">
	<div class='avatarUser_landscape'>
		<img style="width:60px; padding: 5px" src="<?= 'http://fotkers.comuf.com/files/' . $viewData[ID]. '/avatar.jpg'?>">
	</div>
	
	<div style = "margin-left:80px; width:338px;">
		<?  if (isset($user)): ?>
			<? if ($user[User::Username] == $viewData[User::Username]): ?>
				<h1 style = "margin: 0;">Your Photostream</h1>
				<span class="Links">
					<?= $html->link('Photostream', array('controller' => 'images', 'action' => 'photostream', $viewData[User::Username]), array('style' => 'color: #0259C4; text-decoration: none; border-right: 1px dotted; margin: 0 5px 0 0; padding: 0 5px 0 0;'));?>
					<?= $html->link('Profile', array('controller' => 'users', 'action' => 'view', $viewData[ID]),array('style' => 'color: #0259C4; text-decoration: none; border-right: 1px dotted; margin: 0 5px 0 0; padding: 0 5px 0 0;'));?>
					<?= $html->link('Galleries', array('controller' => 'albums', 'action' => 'all', $viewData[ID]),array('style' => 'color: #0259C4; text-decoration: none; border-right: 1px dotted; margin: 0 5px 0 0; padding: 0 5px 0 0;'));?>
					<?= $html->link('Favorites', array('controller' => 'favs', 'action' => 'view', $viewData[ID]),array('style' => 'color: #0259C4; text-decoration: none; border-right: 1px dotted; margin: 0 5px 0 0; padding: 0 5px 0 0;'));?>
					<?= $html->link('Popular', array('controller' => 'favs', 'action' => 'most_popular_image', $viewData[ID]),array('style' => 'color: #0259C4; text-decoration: none; margin: 0 5px 0 0; padding: 0 5px 0 0;'));?>
				</span>
			<? else :?>
				<h2 style="margin-bottom:5px;margin-top:0;"><?= $viewData[User::Username]?>'s photostream</h2>
				<span class="Links">
					<?= $html->link('Photostream', array('controller' => 'images', 'action' => 'photostream', $viewData[User::Username]), array('style' => 'color: #0259C4; text-decoration: none; border-right: 1px dotted; margin: 0 5px 0 0; padding: 0 5px 0 0;'));?>
					<?= $html->link('Profile', array('controller' => 'users', 'action' => 'view', $viewData[ID]),array('style' => 'color: #0259C4; text-decoration: none; border-right: 1px dotted; margin: 0 5px 0 0; padding: 0 5px 0 0;'));?>
					<?= $html->link('Galleries', array('controller' => 'albums', 'action' => 'all', $viewData[ID]),array('style' => 'color: #0259C4; text-decoration: none; border-right: 1px dotted; margin: 0 5px 0 0; padding: 0 5px 0 0;'));?>
					<?= $html->link('Favorites', array('controller' => 'favs', 'action' => 'view', $viewData[ID]),array('style' => 'color: #0259C4; text-decoration: none; border-right: 1px dotted; margin: 0 5px 0 0; padding: 0 5px 0 0;'));?>
					<?= $html->link('Popular', array('controller' => 'favs', 'action' => 'most_popular_image', $viewData[ID]),array('style' => 'color: #0259C4; text-decoration: none; margin: 0 5px 0 0; padding: 0 5px 0 0;'));?>
				</span>
			<? endif; ?>
		<? else: ?>
				<h2 style = "margin: 0;"><?= $viewData[User::Username]?>'s photostream</h2>
				<span class="Links">
					<?= $html->link('Photostream', array('controller' => 'images', 'action' => 'photostream', $viewData[User::Username]), array('style' => 'color: #0259C4; text-decoration: none; border-right: 1px dotted; margin: 0 5px 0 0; padding: 0 5px 0 0;'));?>
					<?= $html->link('Profile', array('controller' => 'users', 'action' => 'view', $viewData[ID]),array('style' => 'color: #0259C4; text-decoration: none; border-right: 1px dotted; margin: 0 5px 0 0; padding: 0 5px 0 0;'));?>
					<?= $html->link('Galleries', array('controller' => 'albums', 'action' => 'all', $viewData[ID]),array('style' => 'color: #0259C4; text-decoration: none; border-right: 1px dotted; margin: 0 5px 0 0; padding: 0 5px 0 0;'));?>
					<?= $html->link('Favorites', array('controller' => 'favs', 'action' => 'view', $viewData[ID]),array('style' => 'color: #0259C4; text-decoration: none; border-right: 1px dotted; margin: 0 5px 0 0; padding: 0 5px 0 0;'));?>
					<?= $html->link('Popular', array('controller' => 'favs', 'action' => 'most_popular_image', $viewData[ID]),array('style' => 'color: #0259C4; text-decoration: none; margin: 0 5px 0 0; padding: 0 5px 0 0;'));?>
				</span>
		<? endif; ?>
	</div>
</div>