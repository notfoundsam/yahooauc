<?php 
$redirect = Uri::segment(3) ?  Uri::segment(2).'/'.Uri::segment(3) : Uri::segment(2);
?>

<div style="margin:30px;"></div>

<?php if ($items): ?>

<?php foreach ($items as $item): ?>	
<?php
$count = 0;
$summ = 0;
$vendors = [];
$post_index = '';
$address = '';
?>

<?php foreach ($item->auctions as $auction): ?>
<?php
$count += $auction->item_count;
$summ += $auction->price;
if (isset($auction->vendor->name) && !in_array($auction->vendor->name, $vendors))
{
	$vendors[] = $auction->vendor->name;
}
if (isset($auction->vendor->post_index) && !$post_index) 
{
	$post_index = $auction->vendor->post_index;
}
if (isset($auction->vendor->address) && !$address) 
{
	$address = $auction->vendor->address;
}
?>
<div class="item-wrapper">
	<div class="count-circle"></div>
	<div class="count"><?= $auction->item_count; ?></div>
	<div class="aucid"><?= $auction->auc_id; ?></div>
	<div class="title"><?= $auction->title; ?></div>
	<div class="price"><?= $auction->price; ?></div>
	<div class="date"><?= Date::create_from_string($auction->won_date, 'mysql')->format('display_date'); ?></div>
	<div class="action">
		<span class="glyphicon glyphicon-info-sign"></span>
		<span class="glyphicon glyphicon-edit"></span>
		<span class="glyphicon glyphicon-remove"></span>
	</div>
</div>

<!-- <?= Html::anchor('admin/auction/edit/'.$auction->id.'/'.$redirect, 'Edit'); ?> |
<?= Html::anchor('admin/auction/delete/'.$auction->id.'/'.$redirect.'?'.\Config::get('security.csrf_token_key').'='.\Security::fetch_token(), 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?> -->
				
<?php endforeach; ?>

<div class="part-wrapper">
	<div class="count-circle"></div>
	<div class="count"><?= $count ?></div>
	<div class="tracking">Tracking: 3039-3030-9982<?= $item->tracking; ?></div>
	<div class="box">Box: 12<?= $item->tracking; ?></div>
	<div class="ship">Ship: <?= $item->price; ?></div>
	<div class="items-price">Summ: <?= $summ; ?></div>
	<div class="total-price">Total: <?= $item->price + $summ; ?></div>
	<div class="action">
		<span class="glyphicon glyphicon-arrow-up"></span>
		<span class="glyphicon glyphicon-arrow-down"></span>
		<span class="glyphicon glyphicon-info-sign"></span>
		<span class="glyphicon glyphicon-edit"></span>
		<span class="glyphicon glyphicon-remove"></span>
	</div>
</div>
		
			<table class="table" >
				<tr>
					<td>
						<b>Vendor's ID: 
						<?php foreach ($vendors as $vendor => $name): ?>
							<font color="red"><?= $name ?></font>, 
						<?php endforeach; ?>
						Part's ID: <?= $item->id ?>, Post Index: <font color="blue"><?= $post_index ?></font>, Address: </b><?= $address ?>
					</td>
					<td style="text-align:right;">
						<?= Html::anchor('admin/part/edit/'.$item->id.'/'.$redirect, 'Edit'); ?> | 
						<?= Html::anchor('admin/part/delete/'.$item->id.'/'.$redirect.'?'.\Config::get('security.csrf_token_key').'='.\Security::fetch_token(), 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>
					</td>
				</tr>
			</table>
		
<?php endforeach; ?>

<?php else: ?>
	<h4>Nothing for show...</h4>
<?php endif; ?>
