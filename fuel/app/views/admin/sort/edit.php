<h2>Editing Sort</h2>
<br>
<p><strong>Vendor:</strong>
	<?= $auction->vendor->name; ?></p>
<p><strong>Auction ID:</strong>
	<?= $auction->auc_id; ?></p>
<?php echo render('admin/sort/_form'); ?>
<p><?php echo Html::anchor('admin/sort/', 'Back'); ?></p>
