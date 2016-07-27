<div class="pagination-box">
<?= \Pagination::instance('default')->render(); ?>
</div>
<p>
	
</p>
<?php if ($vendors): ?>
<table class="table table-striped vendor">
	<thead>
		<tr>
			<th>Name</th>
			<th width="75px">By now</th>
			<th width="100px">Post index</th>
			<th>Address</th>
			<th>Memo</th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($vendors as $item): ?>		<tr>

			<td><?php echo $item->name; ?></td>
			<td style="text-align:center;"><?= $item->by_now? 'Yes' : 'No'; ?></td>
			<td><?php echo $item->post_index; ?></td>
			<td><?php echo $item->address; ?></td>
			<td><?php echo $item->memo; ?></td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Vendors.</p>

<?php endif; ?>
