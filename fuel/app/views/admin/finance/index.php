<table class="table table-striped">
	<thead>
		<tr>
			<th>Balance USD</th>
			<th>USD withdrawaled</th>
			<th>JPY Received</th>
			<th>Balance JPY</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php echo $usd_balance; ?></td>
			<td><?php echo $usd; ?></td>
			<td><?php echo $jpy; ?></td>
			<td><?php echo 0; ?></td>
		</tr>
	</tbody>
</table>

<p>
	<?php echo Html::anchor('admin/finance/create', 'Add new record', array('class' => 'btn btn-success')); ?>
</p>
<?php if ($finance): ?>
<table class="table table-striped vendor">
	<thead>
		<tr>
			<th>Operation Date</th>
			<th>Descriotion</th>
			<th>USD</th>
			<th>JPY</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($finance as $item): ?>
		<tr>

			<td><?php echo $item->operationData; ?></td>
			<td><?php echo $item->memo; ?></td>
			<td><?php echo $item->usd; ?></td>
			<td><?php echo $item->jpy; ?></td>
			<td style="text-align:right;">
				<?php echo Html::anchor('admin/balance/edit/'.$item->id, 'Edit'); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>

<?php else: ?>
<p>Nothing for show.</p>

<?php endif; ?>
