<h2>Editing Vendor</h2>
<br>

<?php echo render('admin/vendor/_form'); ?>
<p>
	<?php echo Html::anchor('admin/vendor/view/'.$vendor->id, 'View'); ?> |
	<?php echo Html::anchor('admin/vendor', 'Back'); ?></p>
