<table class="table table-striped">
	<thead>
		<tr>
			<th>Bought items</th>
			<th>My commission</th>
			<th>Total price</th>
			<th>Approx by one</th>
			<th>Not paid</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?= number_format($item_count) ;?></td>
			<td><?= number_format($commission) ;?></td>
			<td><?= number_format($price) ;?></td>
			<td><?= number_format($approx_price) ;?></td>
			<td><?= number_format($not_paid_sum) ;?></td>
			
		</tr>
	</tbody>
</table>

<?php if (false): ?>
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
	
	</tbody>
</table>

<?php else: ?>
<p>Nothing for show.</p>

<?php endif; ?>
