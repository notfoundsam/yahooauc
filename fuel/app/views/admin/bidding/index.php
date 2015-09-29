<?php if (!empty(Arr::get($table, 'pages' ))) : ?>
<p> Go to page: 

	<?php foreach (Arr::get($table, 'pages' ) as $page) : ?>
	<?php echo Html::anchor('admin/bidding/'.$page, $page);?>
	<?php endforeach ; ?>
</p>
<?php endif ; ?>
<?php if (!empty(Arr::get($table, 'auctions' ))) : ?>

<table class="table table-striped bidding">
	<thead>
		<tr>
			<th>Title</th>
			<th>Price</th>
			<th>Count of bid</th>
			<th>Seller</th>
			<th>Current bidder</th>
			<th>Left time</th>
		</tr>
	</thead>
	<tbody>	
		<?php foreach (Arr::get($table, 'auctions' ) as $tr) : ?>
		<tr class="<?= $tr[4] != Config::get('my.yahoo_user') ? 'bidup' : ''?>">
			<?php foreach ($tr as $td) : ?>
				<td><?= $td?></td>
			<?php endforeach ; ?>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php endif ; ?>

<ul class="nav nav-pills">
	<li class='<?php echo Arr::get($subnav, "index" ); ?>'><?php echo Html::anchor('admin/bidding/index','Index'); ?></li>

</ul>
<p>Index</p>