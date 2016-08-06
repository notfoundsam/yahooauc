<div class="add-vendor">
	<div class="add-button">
		<i class="fa fa-plus-circle" aria-hidden="true"></i>
	</div>
</div>

<div class="pagination-box">
<?= \Pagination::instance('default')->render(); ?>
</div>

<?php if ($vendors): ?>

<div class="conteiner-wrapper">

	<div class="vendor-wrapper item-title">
		<div class="vendor-name">Vendor name</div>
		<div class="by-now">By now</div>
		<div class="post-index">Post index</div>
		<div class="address">Address</div>
	</div>

<?php foreach ($vendors as $item): ?>

	<div class="vendor-wrapper" vendor-id="<?= $item->id; ?>" data-memo="<?= $item->memo; ?>" data-by-now="<?= $item->by_now; ?>">
		<div class="vendor-name"><?= $item->name; ?></div>
		<div class="by-now"><?= $item->by_now ? '<i class="fa fa-check-circle-o" aria-hidden="true"></i>' : ''; ?></div>
		<div class="post-index"><?= $item->post_index; ?></div>
		<div class="address"><?= $item->address; ?></div>
		<div class="action">
			<i class="fa fa-comment<?= $item->memo ? '' : ' hidden'; ?>" aria-hidden="true"></i>
			<?php if (Auth::member(\Config::get('my.groups.superadmin'))) : ?>
			<i class="fa fa-pencil-square-o vendor-edit-button" aria-hidden="true"></i>
			<?php endif; ?>
		</div>
	</div>
					
<?php endforeach; ?>

</div>

<?php else: ?>
<p>No Vendors.</p>

<?php endif; ?>
