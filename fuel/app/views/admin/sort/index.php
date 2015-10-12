
<?php if (!empty($items)) : ?>
<?php
$count = 0;
?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Count</th>
			<th>Auction ID</th>
			<th>Description</th>
			<th>Price</th>
			<th>Won date</th>
			<th>Seller</th>
			<th>Bidder</th>
			<th></th>
		</tr>
	</thead>
	<tbody>	
		<?php foreach ($items as $auction): ?>
			<?php
			$count += $auction->item_count;
			?>
			<tr>
				<td><?= $auction->item_count; ?></td>
				<td><?= $auction->auc_id; ?></td>
				<td><?= $auction->description; ?></td>
				<td><?= $auction->price; ?></td>
				<td><?= $auction->won_date; ?></td>
				<td><?= $auction->vendor->name; ?></td>
				<td><?= $auction->user->username; ?></td>
				<td style="text-align:right;">
					<?php echo Html::anchor('admin/auction/edit/'.$auction->id.'/'.$redirect, 'Edit'); ?> |
					<?php echo Html::anchor('admin/auction/delete/'.$auction->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php endif ; ?>


<ul class="nav nav-pills">
	<li class='<?php echo Arr::get($subnav, "index" ); ?>'><?php echo Html::anchor('admin/sort/index','Index');?></li>

</ul>
<p>Index</p>
<button class="refresh">refresh won</button>
<?= Asset::js('http://code.jquery.com/jquery-1.11.3.min.js')?>
<script type="text/javascript">
	$('.refresh').click(function(){ 
		$.ajax({
            url: '/admin/api/refresh',
            type: 'POST',
            data: { csrf_token_key: "<?= \Security::fetch_token();?>"},
            success: function (result) {
              alert("Your bookmark has been saved");
            }
        });  
    });

</script>