<h2>Listing sorts</h2>
<br>
<?php if ($sorts): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Item count</th>
			<th>Auc id</th>
			<th>Description</th>
			<th>Price</th>
			<th>Won date</th>
			<th>Vendor</th>
			<th>Won user</th>
			<th>Part id</th>
			<th>Memo</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($sorts as $item): ?>		<tr>

			<td><?php echo $item->item_count; ?></td>
			<td><?php echo $item->auc_id; ?></td>
			<td><?php echo $item->description; ?></td>
			<td><?php echo $item->price; ?></td>
			<td><?php echo $item->won_date; ?></td>
			<td><?php echo $item->vendor; ?></td>
			<td><?php echo $item->won_user; ?></td>
			<td><?php echo $item->part_id; ?></td>
			<td><?php echo $item->memo; ?></td>
			<td>
				<?php echo Html::anchor('admin/sort/edit/'.$item->id, 'Edit'); ?>
			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No sorts.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('admin/sort/create', 'Add new sort', array('class' => 'btn btn-success')); ?>

</p>
