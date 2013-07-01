<!--<?= $javascript->link(array('image')); ?>-->
<? $user = $session->read(); ?>

<fieldset>
	<legend> Tags</legend>
	<ul>
	<? foreach ($tags as $tag) : ?>
		<li class="tags">
			<?= $tag[TAG][Tag::Title]; ?>
			<?= $ajax->link('[x]', array('controller' => 'tags', 'action' => 'deleteTag', $tag['ImagesTags'][ID], $imageId),
														  array('update' => 'tags')); ?>
		</li> 
	<? endforeach; ?>
	</ul>
	
	<div style="margin-bottom: 10px;">
		<a id='addTagsLink' class='linkToButton'> Add Tags</a>
	</div>
	
	<div id="formAddTags" style="display: none;">
		<?= $ajax->form(array('type' => 'post',
					'options' => array(
						'id' => 'addTagsForm',	
						'update' => 'tags',
				 		'url' => array(
 							'controller' => 'tags',
 							'action' => 'addTags'
		 		))
		 	)
	 	);?>
	 	<?= $form->input(Tag::T_Title, array('label' => '', 'style' => 'width: 200px;')); ?>
		<?= $form->input('user_id', array('type' => 'hidden', 'value' => $user[USER][ID])); ?>
		<?= $form->input('image_id', array('type' => 'hidden', 'value' => $imageId)); ?>
		<?= $form->end('Add'); ?>
		OR
		<a id="buttonCloseNewTags" class='linkToButton'>Cancel</a>
	</div>
</fieldset>