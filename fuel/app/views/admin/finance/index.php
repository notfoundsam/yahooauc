<table class="table table-striped">
	<thead>
		<tr>
			<th>Balance USD</th>
			<th>USD Withdrawal</th>
			<th>JPY Receive</th>
			<th>Balance JPY</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?= number_format($usd_balance, 2, '.', ','); ?></td>
			<td><?= number_format($usd, 2, '.', ','); ?></td>
			<td><?= number_format($jpy); ?></td>
			<td><?= number_format($balance); ?></td>
		</tr>
	</tbody>
</table>

<div class="pagination-box">
<?= \Pagination::instance('default')->render(); ?>
</div>
<p>
	<?php echo Html::anchor('admin/finance/create', 'Add new record', array('class' => 'btn btn-success')); ?>
</p>
<?php if ($finances): ?>
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
	<?php foreach ($finances as $item): ?>
		<tr>

			<td><?php echo $item->operationData; ?></td>
			<td><?php echo $item->memo; ?></td>
			<td><?php echo number_format($item->usd, 2, '.', ','); ?></td>
			<td><?php echo number_format($item->jpy); ?></td>
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
