<? foreach($comments as $comment) : ?>
	<div id="comment_<?= $comment[COMMENT][ID];?>" class="comment-block">
		<div class="comment-owner">
			<img style="width: 80px;" src="<?= "http://fotkers.comuf.com/files/" . $comment[COMMENT][Comment::UserId] . '/avatar.jpg';?>"> 
		</div>
		<div class="comment-content">
			<h4><?= $html->link($comment[USER][User::Username],array('controller' => 'images', 'action' => 'photostream', $comment[USER][User::Username]))?> says:</h4>
			<p>	<?= $comment[COMMENT][Comment::Value];?><br/>
			<span>
				<span> posted <?= $comment[COMMENT]['created'];?></span>
				(<span> <?= $ajax->link('delete',
												'/comments/deleteComment/'. $comment[COMMENT][ID] . '/' . $comment[COMMENT][Comment::ImageId], 
							 					array('update' => 'commentsPanel', 'complete' => "updateNumberOfComments('remove');", null, false));
 				?></span> |
				 <span> <?= $html->link('edit', array('controller' => 'images', 'action' => 'photostream', $comment[USER][User::Username])); ?></span>)
			</span>
			
			</p>
		</div>
		
	</div>
<? endforeach; ?>