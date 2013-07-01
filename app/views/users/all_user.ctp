<? $url = "http://fotkers.comuf.com/files/"?>
	<table id="table_all_user" >
		<th>#</th>
		<th>Avatar</th>
		<th class="username"><?= $paginator->sort('Username', User::Username) ?></th> 
		<th>Firstname</th>
		<th>Lastname</th>
		<th></th>
	</tr>
	<? foreach($users as $index => $user):?>
		<? $class = ($index % 2) ? "even" : "odd";?>
		<tr class= <?= $class?> title=<?= $user[USER][User::Username]?>>
			<td ><?= ($index + 1)?></td>
			<td class="avatar">
				<?= $html->image($url . $user[USER][ID] . '/avatar.jpg');?>
			</td>
			<td><?= $html->link($user[USER][User::Username], array('action' => 'view', $user[USER][ID]));?></td>
			<td><?= $user[USER][User::Firstname]?></td>
			<td><?= $user[USER][User::Lastname]?></td>
			<td><?= $html->link('view photostream', array('controller'=>'images', 'action'=> 'photostream', $user[USER][User::Username]))?></td>
		</tr>
	<? endforeach;?>
</table>
<div style="margin-top: 10px">
	<?= $html->div('previous_linlk',$paginator->prev('<< Previous', array('class' => 'PrevPg'), null, array('class' => 'PrevPg DisabledPgLk'))) ?>
	<div class="paginatot_numbers"><?= $paginator->numbers() ?></div>
	<?= $html->div('next_linlk', $paginator->next('Next >>', array('class' => 'NextPg'), null, array('class' => 'NextPg DisabledPgLk'))) ?>
</div>
