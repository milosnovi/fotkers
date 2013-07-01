<?php echo $html->css('fullSizeImageLayout'); ?>
<?php echo $javascript->link(array('jquery-1.3.2.min.js')); ?>
<?php echo $javascript->link(array('jquery.form.js')); ?>

<body id="body" bgcolor="#000000">
	<div class="showFullSizeImage">
		<?php echo $content_for_layout; ?>
	</div>
</body>