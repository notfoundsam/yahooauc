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
		<div class="bidding-action">
			<i class="fa fa-arrow-up" aria-hidden="true"></i>
			<i class="fa fa-eye" aria-hidden="true"></i>
		</div>
		<div class="aucid"><?= $item[0]; ?></div>
		<div class="bidding-title"><?= Str::truncate($item[1], 32); ?></div>
		<div class="price<?= $item[5] != \Config::get('my.yahoo.user_name') ? ' price-up' : ''?>"><?= $item[2]; ?></div>
		<div class="count"><?= $item[3]; ?></div>
		<div class="time-left"><?= $item[6]; ?></div>
		<div class="bidder"><?= $item[5] == \Config::get('my.yahoo.user_name') ? 'me' : $item[5]; ?></div>
		<div class="vendor"><?= $item[4]; ?></div>
	</div>
	<?php endforeach; ?>

<?php else : ?>
	<h4>Nothing for show...</h4>
<?php endif ; ?>
