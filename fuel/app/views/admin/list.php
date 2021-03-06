<?php if (Uri::segment(2) === 'shipped'): ?>
<?php if ($count_in_part !== 0): ?>
<div class="shipped-count">
	<div class="sell-count">
		Item in part: <?= $count_in_part; ?>
	</div>
</div>
<?php endif; ?>
<div class="shipped-box">
<?php foreach ($ships as $ship): ?>
	<a href="<?= \Uri::create('admin/shipped/index/'.$ship->shipNumber); ?>" class="<?= $ship->shipNumber == $ship_id ? 'selected' : ''; ?>">
		<div>Ship <?= $ship->shipNumber; ?> : <?= $ship->shipAuctionID; ?></div>
	</a>
<?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (Uri::segment(2) === 'ship' && Auth::member(\Config::get('my.groups.superadmin'))): ?>
<?= Form::open(); ?>
<div class="create-sell">
	<div class="sell-box">
		<label>Enter ship number:</label>
		<input type="text" name="sell_id">
		<button id="create_ship" class="ladda-button" data-style="zoom-in" data-color="blue" type="submit">
			<span class="ladda-label">Create</span>
		</button>
	</div>
	<div class="sell-count">
		<?= $ship_count; ?>
	</div>
</div>
<?= Form::close(); ?>
<?php endif; ?>

<?php if (!empty($items)): ?>

<?php foreach ($items as $item): ?>	
<?php
$count = 0;
$summ = 0;
?>

<div class="conteiner-wrapper">
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
			<?php if (Auth::member(\Config::get('my.groups.superadmin'))) : ?>
			<i class="fa fa-pencil-square-o auc-edit-button" aria-hidden="true"></i>
			<i class="fa fa-times-circle-o auc-delete-button" aria-hidden="true"></i>
			<?php endif; ?>
		</div>
		<div class="comment hidden"><?= $auction->memo; ?></div>
	</div>
	<div class="item-wrapper image-wrapper" data-auc-id="<?= $auction->id ?>"></div>
					
	<?php endforeach; ?>

	<div class="part-wrapper" part-id="<?= $item->id; ?>" part-status="<?= $item->status; ?>">
		<div class="count"><?= $count ?></div>
		<div class="tracking">Tracking: <span><?= $item->tracking; ?></span></div>
		<div class="box">Box: <span><?= $item->box_number; ?></span></div>
		<div class="ship">Ship: <span><?= $item->price; ?></span></div>
		<div class="items-price">Summ: <span><?= $summ; ?></span></div>
		<div class="total-price">Total: <span><?= $item->price + $summ; ?></span></div>
		<div class="action">
			<?php if (Auth::member(\Config::get('my.groups.superadmin'))) : ?>
			<i class="fa fa-chevron-up" aria-hidden="true"></i>
			<i class="fa fa-chevron-down" aria-hidden="true"></i>
			<?php endif; ?>
			<i class="fa fa-picture-o" aria-hidden="true"></i>
			<i class="fa fa-comment<?= $item->memo ? '' : ' hidden'; ?>" aria-hidden="true"></i>
			<?php if (Auth::member(\Config::get('my.groups.superadmin'))) : ?>
			<i class="fa fa-pencil-square-o part-edit-button" aria-hidden="true"></i>
			<i class="fa fa-times-circle-o part-delete-button" aria-hidden="true"></i>
			<?php endif; ?>
		</div>
		<div class="comment hidden"><?= $item->memo; ?></div>
	</div>
</div>
<?php endforeach; ?>

<?php else: ?>
	<h4>Nothing for show...</h4>
<?php endif; ?>
