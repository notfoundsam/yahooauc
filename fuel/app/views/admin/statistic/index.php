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




<?php foreach ($statistic as $s) : ?>
<table>
	<thead>
		<tr>
			<th>Jan</th>
			<th>Feb</th>
			<th>Mar</th>
			<th>Apr</th>
			<th>May</th>
			<th>Jun</th>
			<th>Jul</th>
			<th>Aug</th>
			<th>Sep</th>
			<th>Oct</th>
			<th>Now</th>
			<th>Dec</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<?php for ($i = 1; $i < 12; $i++): ?>
				<?php if( isset($s[$i]) ) : ?>
			<td><?= $s[$i]['count']; ?></td>
				<?php else : ?>
			<td>---</td>
				<?php endif; ?>
			<?php endfor; ?>
		</tr>
		<tr>
			<?php for ($i = 1; $i < 12; $i++): ?>
				<?php if( isset($s[$i]) ) : ?>
			<td><?= $s[$i]['price']; ?></td>
				<?php else : ?>
			<td>---</td>
				<?php endif; ?>
			<?php endfor; ?>
		</tr>
		<tr>
			<?php for ($i = 1; $i < 12; $i++): ?>
				<?php if( isset($s[$i]) ) : ?>
			<td><?= $s[$i]['aprox']; ?></td>
				<?php else : ?>
			<td>---</td>
				<?php endif; ?>
			<?php endfor; ?>
		</tr>
	</tbody>
</table>
<?php endforeach; ?>
