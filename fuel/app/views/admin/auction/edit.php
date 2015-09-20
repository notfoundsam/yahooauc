<p><strong>Vendor:</strong>
	<?= $auction->vendor->name; ?></p>
<p><strong>Auction ID:</strong>
	<?= $auction->auc_id; ?></p>
<?php echo render('admin/auction/_form'); ?>
<p><?php echo Html::anchor('admin/'.$redirect, 'Back'); ?></p>
