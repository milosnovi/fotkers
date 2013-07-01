<?= $javascript->link(array('fullSize')); ?>
<? 
	$url = "http://fotkers.comuf.com/files/";
	$orientation = $imageData[IMAGE][Image::Orientation];
?>
<img style='width:<?=(('portrait' == $orientation) ? '550px' : '850px');?>' src="<?= $url . $imageData[ALBUM][Album::UserId] .'/Original/' . $imageData[IMAGE][ID] . '.' . $imageData[IMAGE][Image::Type]; ?>" />
<br/>

<div id="changeBackgroundColor">
	<?= $html->image('blank_popup.gif', array('id' => '#ffffff','style' => 'background-color: #ffffff;'));?>
	<?= $html->image('blank_popup.gif', array('id' => '#e5e5e5','style' => 'background-color: #e5e5e5'));?>
	<?= $html->image('blank_popup.gif', array('id' => '#cccccc','style' => 'background-color: #cccccc'));?>
	<?= $html->image('blank_popup.gif', array('id' => '#b3b3b3','style' => 'background-color: #b3b3b3'));?>
	<?= $html->image('blank_popup.gif', array('id' => '#999999','style' => 'background-color: #999999'));?>
	<?= $html->image('blank_popup.gif', array('id' => '#808080','style' => 'background-color: #808080'));?>
	<?= $html->image('blank_popup.gif', array('id' => '#666666','style' => 'background-color: #666666'));?>
	<?= $html->image('blank_popup.gif', array('id' => '#4d4d4d','style' => 'background-color: #4d4d4d'));?>
	<?= $html->image('blank_popup.gif', array('id' => '#333333','style' => 'background-color: #333333'));?>
	<?= $html->image('blank_popup.gif', array('id' => '#1a1a1a', 'style' => 'background-color: #1a1a1a'));?>
	<?= $html->image('blank_popup.gif', array('id' => '#000000', 'style' => 'background-color: #000000'));?>
</div>
<div>
	<?= $html->link('Close window', array(), array('id' => 'close_action', 'class' => 'close_button'))?>
</div>
