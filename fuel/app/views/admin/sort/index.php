<div class="alert alert-dismissable ajax" style="display: none;">
	<button type="button" class="close" onclick="$('.alert').hide()">&times;</button>
	<p></p>
</div>
<?php if (!empty($items)) : ?>
<?php
$count = 0;
?>
<?= \Form::open(); ?>
<?= \Form::csrf(); ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th><input type="checkbox" id="select_all"/></th>
			<th>Auction ID</th>
			<th>Title</th>
			<th>Price</th>
			<th>Won date</th>
			<th>Seller</th>
			<th>Bidder</th>
			<th></th>
		</tr>
	</thead>
	<tbody>	
		<?php foreach ($items as $auction): ?>
			<?php
			$count += $auction->item_count;
			?>
			<tr>
				<td><?= \Form::checkbox('ids[]', $auction->id); ?></td>
				<td><?= $auction->auc_id; ?></td>
				<td><?= $auction->title; ?></td>
				<td><?= $auction->price; ?></td>
				<td><?= $auction->won_date; ?></td>
				<td><?= $auction->vendor->name; ?></td>
				<td><?= $auction->user->username; ?></td>
				<td style="text-align:right;">
					<?php echo Html::anchor('admin/sort/edit/'.$auction->id.'/'.Uri::segment(2), 'Edit'); ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<div style="margin-top:10px;text-align:right;">
	<?= \Form::label('Create new part or add to exists:'); ?>
	<?= \Form::input('part_id'); ?>
	<?= \Form::submit('','Create/Add to'); ?>
	<?= \Form::close(); ?>	
</div>
<?php else : ?>
	<h4>No auctions for sort...</h4>
<?php endif ; ?>

<div class="sort-form">
	<label>Count of page for refresh:</label>
	<input type="text" value="1">
	<button class="ladda-button" data-style="zoom-in" data-size="s" data-color="blue">
		<span class="ladda-label">Refresh</span>
	</button>
</div>
