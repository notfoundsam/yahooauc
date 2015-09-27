<?php if (!empty($items)) : ?>
<?php
$count = 0;
?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Count</th>
			<th>Auction ID</th>
			<th>Description</th>
			<th>Price</th>
			<th>Won date</th>
			<th>Seller</th>
			<th>Bidder</th>
		</tr>
	</thead>
	<tbody>	
		<?php foreach ($items as $auction): ?>
			<?php
			$count += $auction->item_count;
			?>
			<tr>
				<td><?= $auction->item_count; ?></td>
				<td><?= $auction->auc_id; ?></td>
				<td><?= $auction->description; ?></td>
				<td><?= $auction->price; ?></td>
				<td><?= $auction->won_date; ?></td>
				<td style="text-align:right;">
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php endif ; ?>


<ul class="nav nav-pills">
	<li class='<?php echo Arr::get($subnav, "index" ); ?>'><?php echo Html::anchor('admin/sort/index','Index');?></li>

</ul>
<p>Index</p>