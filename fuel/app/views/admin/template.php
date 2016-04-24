<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title><?php echo $title; ?></title>
	<?php echo Asset::css(['bootstrap.css', 'main.css', 'dist/ladda-themeless.min.css']); ?>
</head>
<body>
	<?php if ($current_user): ?>
	<?= render('admin/navigation'); ?>
	<?php endif; ?>

<?php if (Session::get_flash('success')): ?>
				<div class="alert alert-success alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<p>
					<?php echo implode('</p><p>', (array) Session::get_flash('success')); ?>
					</p>
				</div>
<?php endif; ?>
<?php if (Session::get_flash('error')): ?>
				<div class="alert alert-danger alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<p>
					<?php echo implode('</p><p>', (array) Session::get_flash('error')); ?>
					</p>
				</div>
<?php endif; ?>
<main id="panel" class="slideout-panel">
	<div class="menu-header">
		<button class="btn-hamburger">
			<span class="hamburger-icon"></span>
		</button>
		<h1><?= $title; ?></h1>
	</div>
	<div class="wrapper">
	<?php echo $content; ?>
	</div>
</main>
<?= Asset::js(['main.js', 'dist/spin.min.js', 'dist/ladda.min.js']); ?>
</body>
</html>
