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

<table class="table table-striped">
	<thead>
		<tr>
			<th>On hand + won</th>
			<th>On hand</th>
			<th>Not paid</th>
			<th>Waiting vendors</th>
			<th>Today</th>
			<th>Yesterday</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?= $on_hand_won; ?></td>
			<td><?= $on_hand; ?></td>
			<td></td>
			<td></td>
			<td><?= $today_won; ?></td>
			<td><?= $yesterday_won; ?></td>
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
