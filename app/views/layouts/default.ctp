<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>	<?= $title_for_layout; ?> </title>
		<?= $javascript->link(array('jquery-1.3.2.min.js', 'all_project_script.js', 'image')); ?>
		<?= $javascript->link(array('jquery.form.js')); ?>
		<?= $html->css('myStyle'); ?>
	</head>
	<body>
		<div id="container">
			<div id="header">
				<?= $this->element("header");?>
			</div>
						
			<div id="userMenu">
			<?php $loggedUser = $session->read(); ?>
				<?php if (isset($loggedUser[USER])): ?>
					<?= $this->element("userMenu");?>
				<?php endif;?>
			</div>
			<div id="content">
				<?= $content_for_layout; ?>
			</div>
			
			<div id="footer">
			</div>
			
		</div>
	</body>
</html>