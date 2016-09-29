<?php if (!empty($table['pages'])) : ?>
<p> Go to page: 

	<?php foreach ($table['pages'] as $page) : ?>
	<?php echo Html::anchor('admin/bidding/'.$page, $page);?>
	<?php endforeach ; ?>
</p>
<?php endif ; ?>
<?php if (!empty($table['auctions'])) : ?>

	<?php foreach ($table['auctions'] as $item) : ?>
	<div class="item-wrapper">
		<div class="bidding-wrap <?= $item[5] != \Config::get('my.yahoo.user_name') ? ' price-up' : ''?>">
			<div class="bidding-img">
		<?php if ($item['images']) : ?>
			<?php foreach ($item['images'] as $img) : ?>
				<div><img src="<?= $img; ?>"></div>
			<?php endforeach; ?>
		<?php endif; ?>
			</div>
			<div class="bidding-content">
				<div><?= $item[1]; ?></div>
				<div><span>Price:</span> <?= $item[2]; ?></div>
				<div><span>Time left:</span> <?= $item[6]; ?></div>
				<div><span>Vendor:</span> <?= $item[4]; ?></div>
				<div><span>Bids:</span> <?= $item[3]; ?></div>
				<div><span>ID:</span> <?= $item[0]; ?></div>
				<div><span>Current bidder:</span> <?= $item[5] == \Config::get('my.yahoo.user_name') ? 'me' : $item[5]; ?></div>
			</div>
		</div>
	</div>
	<?php endforeach; ?>

<?php else : ?>
	<h4>Nothing for show...</h4>
<?php endif ; ?>
