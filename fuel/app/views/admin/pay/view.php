<h2>Viewing #<?php echo $part->id; ?></h2>

<p>
	<strong>Status:</strong>
	<?php echo $part->status; ?></p>
<p>
	<strong>Price:</strong>
	<?php echo $part->price; ?></p>
<p>
	<strong>Ship number:</strong>
	<?php echo $part->ship_number; ?></p>
<p>
	<strong>Box number:</strong>
	<?php echo $part->box_number; ?></p>
<p>
	<strong>Tracking:</strong>
	<?php echo $part->tracking; ?></p>
<p>
	<strong>Memo:</strong>
	<?php echo $part->memo; ?></p>

<?php echo Html::anchor('admin/part/edit/'.$part->id, 'Edit'); ?> |
<?php echo Html::anchor('admin/part', 'Back'); ?>