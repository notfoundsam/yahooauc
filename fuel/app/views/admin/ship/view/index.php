<h2>Listing ship</h2>
<br>
<?php if ($ships): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Status</th>
			<th>Price</th>
			<th>Ship number</th>
			<th>Box number</th>
			<th>Tracking</th>
			<th>Memo</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($ships as $item): ?>		<tr>

			<td><?php echo $item->status; ?></td>
			<td><?php echo $item->price; ?></td>
			<td><?php echo $item->ship_number; ?></td>
			<td><?php echo $item->box_number; ?></td>
			<td><?php echo $item->tracking; ?></td>
			<td><?php echo $item->memo; ?></td>
			<td>
				<?php echo Html::anchor('admin/part/view/'.$item->id, 'View'); ?> |
				<?php echo Html::anchor('admin/part/edit/'.$item->id, 'Edit'); ?> |
				<?php echo Html::anchor('admin/part/delete/'.$item->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No ship.</p>

<?php endif; ?>
