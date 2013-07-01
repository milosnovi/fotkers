<?= $form->create('User', array('action' => 'login'))?>
	<div class= "form_login">
		<?php if ($session->check('Message.flash')):
			$session->flash();
			endif;
		?>
		<legend> 
			<p><?= $form->input('User.username', array('label' =>  array('class' => 'loginForm'),'style' => 'width: 180px;'));?></p>
			<p><?= $form->input('User.password', array('label' =>  array('class' => 'loginForm'),'style' => 'width: 180px;'));?></p>
			<p><?= $form->input('User.remind_me', array(
									'div' => array('class' => 'login_form_checkbox'),
									'type' => 'checkbox','class' => 'loginFormCheckbox'));?></p>
		</legend>
<?= $form->end('log in')?>

</div>

<div style="position: absolute; float: left;">
<?= $html->link('Create account for free', array( 'controller' => 'users', 'action' => 'create'), array('class' => 'link_create_account'))?>
</div>