<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title><?php echo $title; ?></title>
	<?php echo Asset::css(['main.css']); ?>
</head>
<body>
	<?php if ($current_user): ?>
	<?= render('admin/navigation'); ?>
	<?php endif; ?>

<main id="panel" class="slideout-panel">
	<div class="menu-header">
		<button class="btn-hamburger">
			<span class="hamburger-icon"></span>
		</button>
		<h1><?= $title; ?></h1>
	</div>

	<div id="alert" class="<?= Session::get_flash('alert') ? 'alert-' . Session::get_flash('alert.status') : 'alert-hide'; ?>">
		<button type="button" onclick="$('#alert').addClass('alert-hide');">&times;</button>
		<p>
		<?= implode('<br>', (array) Session::get_flash('alert.message')); ?>
		</p>
	</div>

	<div class="wrapper">
	<?php echo $content; ?>
	</div>
</main>
<?= Asset::js(['main.js']); ?>
</body>
</html>
