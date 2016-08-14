<div class="add-finance">
	<div class="add-button">
		<i class="fa fa-plus-circle" aria-hidden="true"></i>
	</div>
</div>

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

<?php if ($finances): ?>

<div class="conteiner-wrapper">

	<div class="finance-wrapper item-title">
		<div class="opertion-date">Operation Date</div>
		<div class="memo">Descriotion</div>
		<div class="finance-usd">USD</div>
		<div class="finance-jpy">JPY</div>
	</div>

<?php foreach ($finances as $item): ?>

	<div class="finance-wrapper" finance-id="<?= $item->id; ?>">
		<div class="opertion-date"><?= $item->operationData; ?></div>
		<div class="memo"><?= $item->memo; ?></div>
		<div class="finance-usd"><?= number_format($item->usd, 2, '.', ','); ?></div>
		<div class="finance-jpy"><?= number_format($item->jpy); ?></div>
		<div class="action">
			<?php if (Auth::member(\Config::get('my.groups.superadmin'))) : ?>
			<i class="fa fa-pencil-square-o finance-delete-button" aria-hidden="true"></i>
			<?php endif; ?>
		</div>
	</div>
					
<?php endforeach; ?>

</div>

<?php else: ?>
<p>No Finances.</p>

<?php endif; ?>
