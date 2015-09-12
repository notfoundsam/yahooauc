<h2>Listing Pay</h2>
<br>
<?php if ($items): ?>

<?php foreach ($items as $item): ?>	
<?php
$count = 0;
$summ = 0;
?>
<table class="table-out">
	<tr>
		<td>
			<table class="table table-striped auction">
				<thead>
					<tr>
						<th>Count</th>
						<th>Auction</th>
						<th>Description</th>
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
					?>
					<tr>
						<td><?php echo $auction->item_count; ?></td>
						<td><?php echo $auction->auc_id; ?></td>
						<td><?php echo $auction->description; ?></td>
						<td><?php echo $auction->price; ?></td>
						<td><?php echo $auction->won_date; ?></td>
						<td><?php echo $auction->memo; ?></td>
						<td style="text-align:right;">
							<?php echo Html::anchor('admin/part/edit/'.$item->id, 'Edit'); ?> |
							<?php echo Html::anchor('admin/part/delete/'.$item->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>
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
						<th></th>
					</tr>
				</thead>
				<tbody>	<tr>
						<td><?= $count ?></td>
						<td><?php echo $item->price; ?></td>
						<td><?php echo $item->box_number; ?></td>
						<td><?php echo $item->tracking; ?></td>
						<td><?= $item->price + $summ; ?></td>
						<td><?php echo $item->memo; ?></td>
						<td style="text-align:right;">
							<?php echo Html::anchor('admin/part/view/'.$item->id, 'View'); ?> |
							<?php echo Html::anchor('admin/part/edit/'.$item->id, 'Edit'); ?> |
							<?php echo Html::anchor('admin/part/delete/'.$item->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>
						</td>
					</tr>
				</tbody>
			</table>
		</td>
	</tr>
</table>
<?php endforeach; ?>
<?php else: ?>

<p>No pay.</p>

<?php endif; ?>
