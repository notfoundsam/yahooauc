<div class="alert alert-dismissable" style="display: none;">
	<button type="button" class="close" onclick="$('.alert').hide()">&times;</button>
	<p></p>
</div>
<?php if (!empty($items)) : ?>
<?php
$count = 0;
?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Count</th>
			<th>Auction ID</th>
			<th>Title</th>
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
				<td><?= $auction->title; ?></td>
				<td><?= $auction->price; ?></td>
				<td><?= $auction->won_date; ?></td>
				<td><?= $auction->vendor->name; ?></td>
				<td><?= $auction->user->username; ?></td>
				<td style="text-align:right;">
					<?php echo Html::anchor('admin/auction/edit/'.$auction->id.'/'.Uri::segment(2), 'Edit'); ?> |
					<?php echo Html::anchor('admin/auction/delete/'.$auction->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php endif ; ?>
<br />
<div style="text-align:center">
	<label>Count of page for refresh:</label> <input id="pages" type="text" value="1">
	<button class="refresh">Refresh</button>
</div>



<script type="text/javascript">
	$('.refresh').click(function(){ 
		var pages = $('#pages').val();
		$.ajax({
			url: '/admin/api/refresh',
			type: 'POST',
			data: {
				csrf_token_key: "<?= \Security::fetch_token();?>",
				pages: pages
			},
			success: function (data) {
				if (data.error.length == 0){

					if (data.result) {
						$('.alert p').html(data.result + " auction was won, refresh the page");
						$('.alert').addClass('alert-success').show();
					}
					else {
						$('.alert p').html("No items was won");
						$('.alert').addClass('alert-success').show();
					}
				}
				else {
					$('.alert p').html("Validation error has occurred, see logs.");
					$('.alert').addClass('alert-danger').show();
				}
			},
			error: function(){
				$('.alert p').html("API error has occurred!");
				$('.alert').addClass('alert-danger').show();
			}
		});
	});
</script>
