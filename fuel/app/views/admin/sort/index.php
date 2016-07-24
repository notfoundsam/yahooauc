<div class="create-part-hide">
	<div class="create-box">
		<label>Create new part or add to exists:</label>
		<input type="text" name="combine_id">
		<button id="create_part" class="ladda-button" data-style="zoom-in" data-color="blue">
			<span class="ladda-label">Create/Add</span>
		</button>
		<button id="select_all" class="ladda-button" data-style="zoom-in" data-color="blue">
			<span class="ladda-label">Check all</span>
		</button>
	</div>
	<div class="refresh-button">
		<i class="fa fa-refresh" aria-hidden="true"></i>
	</div>
	<div class="refresh-confirm">
		<div>
			<label>Page number:</label>
			<input type="text" name="page" value="1">
		</div>
		<button class="ladda-button refresh-won" data-style="zoom-in" data-color="blue">
			<span class="ladda-label">Confirm</span>
		</button>
		<button class="ladda-button cancel" data-style="zoom-in" data-color="blue">
			<span class="ladda-label">Cancel</span>
		</button>
	</div>
</div>
<?php if (!empty($items)) : ?>

	<?php foreach ($items as $item) : ?>
	<div class="sort-wrapper">
		<label class="sort-check">
		<?= \Form::checkbox('ids[]', $item->id); ?>
		</label>
		<div class="aucid"><?= $item->auc_id; ?></div>
		<div class="title"><?= Str::truncate($item->title, 25); ?></div>
		<div class="price"><?= $item->price; ?></div>
		<div class="date"><?= Date::create_from_string($item->won_date, 'mysql')->format('display_date'); ?></div>
		<div class="vendor"><?= $item->vendor->name; ?></div>
		<div class="action">
			
			
			<i class="fa fa-pencil-square-o sort-edit-button" aria-hidden="true"></i>
			<i class="fa fa-share sort-move-button" aria-hidden="true"></i>
			
		</div>
	</div>
	<?php endforeach; ?>

<?php else : ?>
	<h4>Nothing for show...</h4>
<?php endif ; ?>
