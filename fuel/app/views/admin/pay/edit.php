<h2>Editing Part</h2>
<br>

<?php echo render('admin/part/_form'); ?>
<p>
	<?php echo Html::anchor('admin/part/view/'.$part->id, 'View'); ?> |
	<?php echo Html::anchor('admin/part', 'Back'); ?></p>
