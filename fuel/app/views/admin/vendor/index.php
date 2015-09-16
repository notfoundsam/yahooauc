<h2>Listing Vendors</h2>
<br>
<?php if ($vendors): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Name</th>
			<th>By now</th>
			<th>Post index</th>
			<th>Address</th>
			<th>Color</th>
			<th>Memo</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($vendors as $item): ?>		<tr>

			<td><?php echo $item->name; ?></td>
			<td><?php echo $item->by_now; ?></td>
			<td><?php echo $item->post_index; ?></td>
			<td><?php echo $item->address; ?></td>
			<td><?php echo $item->color; ?></td>
			<td><?php echo $item->memo; ?></td>
			<td>
				<?php echo Html::anchor('admin/vendor/view/'.$item->id, 'View'); ?> |
				<?php echo Html::anchor('admin/vendor/edit/'.$item->id, 'Edit'); ?> |
				<?php echo Html::anchor('admin/vendor/delete/'.$item->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Vendors.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('admin/vendor/create', 'Add new Vendor', array('class' => 'btn btn-success')); ?>

</p>
