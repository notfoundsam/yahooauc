<?php 
$redirect = Uri::segment(3) ?  Uri::segment(2).'/'.Uri::segment(3) : Uri::segment(2);
?>
<?php if ($items): ?>

<?php foreach ($items as $item): ?>	
<?php
$count = 0;
$summ = 0;
$vendors = [];
$post_index = '';
$address = '';
?>
<table class="table-out">
	<tr>
		<td>
			<table class="table table-striped auction">
				<thead>
					<tr>
						<th>Count</th>
						<th>Auction ID</th>
						<th>Title</th>
						<th>Price</th>
						<th>Won date</th>
						<th>Memo</th>
						<th></th>
					</tr>
				</thead>
				<tbody>	
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
					<tr>
						<td><?= $auction->item_count; ?></td>
						<td><?= $auction->auc_id; ?></td>
						<td><?= $auction->title; ?></td>
						<td><?= $auction->price; ?></td>
						<td><?= Date::create_from_string($auction->won_date, 'mysql')->format('display_date'); ?></td>
						<td><?= $auction->memo; ?></td>
						<td style="text-align:right;">
							<?= Html::anchor('admin/auction/edit/'.$auction->id.'/'.$redirect, 'Edit'); ?> |
							<?= Html::anchor('admin/auction/delete/'.$auction->id.'/'.$redirect.'?'.\Config::get('security.csrf_token_key').'='.\Security::fetch_token(), 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table class="table part" >
				<thead>
					<tr>
						<th>Count</th>
						<th>Ship price</th>
						<th>Box number</th>
						<th>Tracking</th>
						<th>Totall</th>
						<th>Memo</th>
					</tr>
				</thead>
				<tbody>	<tr>
						<td><?= $count ?></td>
						<td><?= $item->price; ?></td>
						<td><?= $item->box_number; ?></td>
						<td><?= $item->tracking; ?></td>
						<td><?= $item->price + $summ; ?></td>
						<td><?= $item->memo; ?></td>
					</tr>
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table class="table part" >
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
		</td>
	</tr>
</table>
<?php endforeach; ?>
<?php else: ?>

<p>No pay.</p>

<?php endif; ?>
