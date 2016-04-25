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
		<div class="count-circle"></div>
		<div class="aucid"><?= $item[0]; ?></div>
		<div class="title"><?= $item[1]; ?></div>
		<div class="price<?= $item[5] != Config::get('my.yahoo_user') ? ' price-up' : ''?>"><?= $item[2]; ?></div>
		<div class="count"><?= $item[3]; ?></div>
	</div>
	<?php endforeach; ?>

<?php else : ?>
	<h4>Nothing for show...</h4>
<?php endif ; ?>
