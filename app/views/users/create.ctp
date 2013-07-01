<div id="reistration_form">
	<?= $form->create('User', array('action' => 'create'))?>
	<fieldset>
		<legend>REGISTRATION FORM</legend>
		
		<p><?= $form->input(User::Username); ?></p>
		<p><?= $form->input(User::Password); ?></p>
		<p><?= $form->input(User::A_RetypePassword); ?></p>
		<p><?= $form->input(User::Firstname); ?></p>
		<p><?= $form->input(User::Lastname); ?></p>
		<p><?= $form->input(User::Email); ?></p>
		
	</fieldset>
	<?= $form->end('create account')?>

	<?= $html->link('login page', array('controller' => 'users', 'action' => 'login'), array('id' => 'backToLogIn'));?>
</div>
