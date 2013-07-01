<? $url = "http://fotkers.comuf.com/files/" . $user[USER][ID]; ?>

<?= $this->element('user_info', array('viewData' =>  $user[USER]))?>

<div class="uploadAvatarPicture">
	<div class="avatarPicture">
		<img  style="width: 170px;" src = "<?= $url . "/avatar.jpg";?>" />
	</div>
	<?php
	    echo $form->create(IMAGE, array('action' => 'action_upload_avatar', 'type' => 'file'));
	    echo $form->file('File', array('class' => 'submit_button'));
	    echo $form->submit('Upload avatar', array('class' => 'submit_button'));
	    echo $form->end();
	?>
</div>

<div class = "UserDetail">
	<?= $form->create(USER, array('action' => "edit_profile/{$user[USER][ID]}", 'id' => 'form_edit_userinformation'));?>
		<?= $form->input(ID, array('type' => 'hidden', 'value' => $user[USER][ID]));?>
		
		<div id="poruke_o_greskama">
			<? if ($session->check('Message.flash')):
				$session->flash();
				endif;
			?>
		</div>
		
		<p><?= $form->input(User::T_Username, array('label' => array('class' => 'labelForm'),'style' => 'width:300px;', 'value' => $user[USER][User::Username]));?></p>
		<p><?= $form->input(User::T_Firstname, array('label' => array('class' => 'labelForm'),'style' => 'width: 300px;','value' => $user[USER][User::Firstname]));?></p>
		<p><?= $form->input(User::T_Lastname, array('label' => array('class' => 'labelForm'),'style' => 'width: 300px;','value' => $user[USER][User::Lastname]));?></p>
		<p><?= $form->input(User::T_Email, array('label' => array('class' => 'labelForm'),'style' => 'width: 300px;','value' => $user[USER][User::Email]));?></p>
		<p><?= $form->input(User::T_City, array('label' => array('class' => 'labelForm'),'style' => 'width: 300px;','value' => $user[USER][User::City]));?></p>
		<p><?= $form->input(User::T_Country, array('label' => array('class' => 'labelForm'),'style' => 'width: 300px;','value' => $user[USER][User::Country]));?></p>
		<p><?= $form->input(User::T_Zanimanje, array('label' => array('class' => 'labelForm'),'style' => 'width: 300px;','value' => $user[USER][User::Zanimanje]));?></p>
		<p><?= $form->input(User::T_Moto, array('label' => array('class' => 'labelForm'),'rows' => '5', 'cols' => '35', 'value' => $user[USER][User::Moto]));?></p>
		<br/>
	<?= $form->end('submit changes');?>
</div>