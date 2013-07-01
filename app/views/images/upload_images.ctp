<script type="text/javascript">
$(document).ready(function(){
	$('#add_upload_file').click(function() {
		var tmp = "<div>" +
					"<input id='FileImage' type='file' value='' name='data[Image][][File]'/> &nbsp" +
					"<a class='close_form' onclick='removeItem()'>[x]" +
				"</div>";
		$('#all_upload_files').append(tmp);
	});
	
	removeItem = function() {
//		console.log($('#all_upload_files').find("div:last").remove()); // Moze i ovako $("#all_upload_files > div:last").remove()
		$('#all_upload_files').find("div:last").remove();
//		$('#all_upload_files').find("input").filter(function(index){
//				alert(index);
//		});
	}
});
</script>

<?php $user = $session->read(USER);?>
<?php echo $this->element('user_info', array('viewData' => $session->read(USER)));?>

	<?php if (isset($error_mesasge)) : ?>
		<?php echo $error_mesasge; ?>
	<?php endif; ?>
	
<div id="upload_images">
	<?php  echo $form->create('Images', array('action' => 'upload_images', 'type' => 'file')); ?>
	<h3>Upload photos to fotkeSrbija</h3>
	<div id="all_upload_files" style="float: left; width: 400px;">
		<div>
			<input id="FileImage" type="file" name="data[Image][][File]" style="float: left"/>
			<a id="add_upload_file" class="button_add_upload_form"> add </a><br/>&nbsp
		</div>
	</div><!--
	
	ADD tags to all uploaded files
	--><div id="confirm_upload_mulitple_files">
		<?php 
			echo $form->submit('Upload');
			echo $form->end();
		?>
	</div>
</div>
