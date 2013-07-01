<? $username = $session->read(USER); ?>
	<span>
		<?= $html->link('HOME', array('controller' => 'images', 'action' => 'home'), array('class' => 'userMenuButtons'))?> 
	</span> ||
		<?= $html->link('MY PHOTOSTREAM', array('controller' => 'images', 'action' => 'photostream', $username['username']), array('class' => 'userMenuButtons'))?>
	</span> ||
	
	<span>
		<?= $html->link('EDIT PROFILE', array('controller' => 'users', 'action' => 'edit_profile', $username[ID]), array('class' => 'userMenuButtons'))?>
	</span> ||
	
	<span>
		<?= $html->link('ALL USERS', array('controller' => 'users', 'action' => 'all_user'), array('class' => 'userMenuButtons'))?>
	</span> ||
	
	<span>
		<?= $html->link('UPLOAD IMAGES', array('controller' => 'images', 'action' => 'upload_images'), array('class' => 'userMenuButtons'));?>
	</span><!-- ||
	
	<span>
		<?= $html->link('ADMINISTRATION', array('action' => 'home'), array('class' => 'userMenuButtons'))?></span>-->