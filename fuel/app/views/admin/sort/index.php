
<?php if (!empty($items)) : ?>

	<?php foreach ($items as $item) : ?>
	<div class="sort-wrapper">
		<label class="sort-check">
		<?= \Form::checkbox('ids[]', $item->id); ?>
		</label>
		<div class="aucid"><?= $item->auc_id; ?></div>
		<div class="title"><?= Str::truncate($item->title, 38); ?></div>
		<div class="price"><?= $item->price; ?></div>
		<div class="date"><?= Date::create_from_string($item->won_date, 'mysql')->format('display_date'); ?></div>
		<div class="vendor"><?= $item->vendor->name; ?></div>
		<div class="action">
			
			
			<i class="fa fa-pencil-square-o sort-edit-button" aria-hidden="true"></i>
			<i class="fa fa-share sort-move-button" aria-hidden="true"></i>
			
		</div>
	</div>
	<?php endforeach; ?>
<div style="margin-top:10px;text-align:right;">
	<?= \Form::label('Create new part or add to exists:'); ?>
	<?= \Form::input('part_id'); ?>
	<?= \Form::submit('','Create/Add to'); ?>
	<?= \Form::close(); ?>	
</div>
<?php else : ?>
	<h4>Nothing for show...</h4>
<?php endif ; ?>

<div class="sort-form">
	<label>Count of page for refresh:</label>
	<input type="text" value="1">
	<button class="ladda-button" data-style="zoom-in" data-size="s" data-color="blue">
		<span class="ladda-label">Refresh</span>
	</button>
</div>
