<h2>Listing Pay</h2>
<br>
<?php if ($items): ?>

<?php foreach ($items as $item): ?>	

<table class="table-out">
	<tr>
		<td>
			<table class="table table-striped">
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
			<table class="table" >
				<thead>
					<tr>
						<th>Status</th>
						<th>Price</th>
						<th>Ship number</th>
						<th>Box number</th>
						<th>Tracking</th>
						<th>Memo</th>
						<th></th>
					</tr>
				</thead>
				<tbody>	<tr>
						<td><?php echo $item->status; ?></td>
						<td><?php echo $item->price; ?></td>
						<td><?php echo $item->ship_number; ?></td>
						<td><?php echo $item->box_number; ?></td>
						<td><?php echo $item->tracking; ?></td>
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
