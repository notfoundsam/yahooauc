<?php 
$redirect = Uri::segment(3) ?  Uri::segment(2).'/'.Uri::segment(3) : Uri::segment(2);
?>

<?php if ($items): ?>

<?php foreach ($items as $item): ?>	
<?php
$count = 0;
$summ = 0;
?>

<div class="head-wrapper">
	<div class="part-id">ID: <?= $item->id; ?></div>
	<?php if ($item->auctions) : ?>
	<?php $first = current($item->auctions); ?>
	<div class="vendor-id"><?= $first->vendor->name; ?></div>
	<div class="index"><?= $first->vendor->post_index; ?></div>
	<div class="address"><?= $first->vendor->address; ?></div>
	<?php endif; ?>
</div>
	
<?php foreach ($item->auctions as $auction): ?>
<?php
$count += $auction->item_count;
$summ += $auction->price;
?>

<div class="item-wrapper" item-id="<?= $auction->id; ?>">
	<div class="count"><?= $auction->item_count; ?></div>
	<div class="aucid"><?= $auction->auc_id; ?></div>
	<div class="title"><?= $auction->title; ?></div>
	<div class="price"><?= $auction->price; ?></div>
	<div class="date"><?= Date::create_from_string($auction->won_date, 'mysql')->format('display_date'); ?></div>
	<div class="action">
		<i class="fa fa-comment<?= $auction->memo ? '' : ' hidden'; ?>" aria-hidden="true"></i>
		<i class="fa fa-pencil-square-o auc-edit-button" aria-hidden="true"></i>
		<i class="fa fa-times-circle-o auc-delete-button" aria-hidden="true"></i>
	</div>
	<div class="comment hidden"><?= $auction->memo; ?></div>
</div>
				
<?php endforeach; ?>

<div class="part-wrapper">
	<div class="count"><?= $count ?></div>
	<div class="tracking">Tracking: <?= $item->tracking; ?></div>
	<div class="box">Box: <?= $item->box_number; ?></div>
	<div class="ship">Ship: <?= $item->price; ?></div>
	<div class="items-price">Summ: <?= $summ; ?></div>
	<div class="total-price">Total: <?= $item->price + $summ; ?></div>
	<div class="action">
		<i class="fa fa-chevron-up" aria-hidden="true"></i>
		<i class="fa fa-chevron-down" aria-hidden="true"></i>
		<i class="fa fa-comment" aria-hidden="true"></i>
		<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
		<i class="fa fa-times-circle-o" aria-hidden="true"></i>
	</div>
</div>

<?php endforeach; ?>

<?php else: ?>
	<h4>Nothing for show...</h4>
<?php endif; ?>
