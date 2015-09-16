<h2>Viewing #<?php echo $vendor->id; ?></h2>

<p>
	<strong>Name:</strong>
	<?php echo $vendor->name; ?></p>
<p>
	<strong>By now:</strong>
	<?php echo $vendor->by_now; ?></p>
<p>
	<strong>Post index:</strong>
	<?php echo $vendor->post_index; ?></p>
<p>
	<strong>Address:</strong>
	<?php echo $vendor->address; ?></p>
<p>
	<strong>Color:</strong>
	<?php echo $vendor->color; ?></p>
<p>
	<strong>Memo:</strong>
	<?php echo $vendor->memo; ?></p>

<?php echo Html::anchor('admin/vendor/edit/'.$vendor->id, 'Edit'); ?> |
<?php echo Html::anchor('admin/vendor', 'Back'); ?>