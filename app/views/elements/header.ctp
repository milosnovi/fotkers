<? 	$user = $session->read(); ?>
<div id = "siteName">
	<h1><a style="text-decoration: none; color: black; font-weight: bold;" href="http://fotkesrbija.host56.com/SimplePHP/">fotkeRs</a></h1>
</div>
<? if(isset($user['User'])) : ?>
	<span class= "loginStatus">
		<span style ="color: silver"> You are log in as </span> &nbsp; <?= $user['User']['username']; ?>
	</span>
	<?= $html->link('log out', array('controller' => 'users', 'action' => 'logout')) ?>
<? else : ?>
	You are not log in! <?= $html->link('log in', array('controller' => 'users', 'action' => 'login')); ?>
<? endif ; ?>
<br/>


<?= $form->create(IMAGE, array('controller' => IMAGE, 'action' => 'search_action', 'type' => 'post')); ?>
	<?= $form->input('search_title', array('label' => '')); ?>
<?= $form->end('Search'); ?>