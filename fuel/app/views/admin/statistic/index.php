<div class="statistic-box">
	<div class="statistic-row-one">
		<div>Bought items</div>
		<div>My commission</div>
		<div>Total price</div>
		<div>Approx by one</div>
		<div>Not paid</div>
	</div>
	<div class="statistic-row-one">
		<div><?= number_format($item_count); ?></div>
		<div><?= number_format($commission); ?></div>
		<div><?= number_format($price); ?></div>
		<div><?= number_format($approx_price); ?></div>
		<div><?= number_format($not_paid_sum); ?></div>
	</div>
</div>

<div class="statistic-box">
	<div class="statistic-row-two">
		<div>On hand + won</div>
		<div>On hand</div>
		<div>Not paid</div>
		<div>Waiting vendors</div>
		<div>Today</div>
		<div>Yesterday</div>
	</div>
	<div class="statistic-row-two">
		<div><?= $on_hand_won; ?></div>
		<div><?= $on_hand; ?></div>
		<div>---</div>
		<div>---</div>
		<div><?= $today_won; ?></div>
		<div><?= $yesterday_won; ?></div>
	</div>
</div>

<?php foreach ($statistic as $k => $s) : ?>
<div class="statistic-box">
	<div class="statistic-row-year"><?= $k; ?></div>
	<div class="statistic-row-month">
	<?php for ($i = 1; $i < 12; $i++): ?>
		<div class="month"><?= \DateTime::createFromFormat('!m', $i)->format('F'); ?></div>
		<div><?= isset($s[$i]) ? $s[$i]['count'] : '---'; ?></div>
		<div><?= isset($s[$i]) ? $s[$i]['price'] : '---'; ?></div>
		<div><?= isset($s[$i]) ? $s[$i]['aprox'] : '---'; ?></div>
	<?php endfor; ?>
	</div>
</div>
<?php endforeach; ?>
