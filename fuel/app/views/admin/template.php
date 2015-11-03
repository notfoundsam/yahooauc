<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo $title; ?></title>
	<?php echo Asset::css('bootstrap.css'); ?>
	<?php echo Asset::css('admin.css'); ?>
	<?php echo Asset::css('dist/ladda-themeless.min.css'); ?>
	<style>
		body { margin: 50px; }
	</style>
	<?php echo Asset::js(array(
		'http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js',
		'bootstrap.js',
	)); ?>
	<script>
		$(function(){ $('.topbar').dropdown(); });
	</script>
</head>
<body>
	<?php if ($current_user): ?>
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">My Site</a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li class="<?php echo Uri::segment(2) == '' ? 'active' : '' ?>">
						<?php echo Html::anchor('admin', 'Dashboard') ?>
					</li>
					<li class="<?php echo Uri::segment(2) == 'bidding' ? 'active' : '' ?>">
						<a href="<?= \Uri::create('admin/bidding') ?>">Bidding</a>
					</li>
					<li class="dropdown<?= in_array(\Uri::segment(2), ['pay', 'paid', 'received']) ? ' active' : '' ?><?= \Uri::segment(3) == 'view' ? ' active' : '' ?>">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            Won<b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="<?= \Uri::create('admin/pay') ?>">Pay</a>
                            </li>
                            <li>
                                <a href="<?= \Uri::create('admin/paid') ?>">Paid</a>
                            </li>
                            <li>
                                <a href="<?= \Uri::create('admin/received') ?>">Received</a>
                            </li>
                            <li>
                                <a href="<?= \Uri::create('admin/ship/view') ?>">Ship</a>
                            </li>
                            <li>
                                <a href="<?= \Uri::create('admin/sell') ?>">Sell</a>
                            </li>
                        </ul>
                    </li>
                    <li class="<?php echo Uri::segment(2) == 'sort' ? 'active' : '' ?>">
						<a href="<?= \Uri::create('admin/sort') ?>">Sort</a>
					</li>
					<li class="<?php echo Uri::segment(3) == 'create' ? 'active' : '' ?>">
						<a href="<?= \Uri::create('admin/ship/create') ?>">Create Ship</a>
					</li>
					<li class="<?php echo Uri::segment(2) == 'vendor' ? 'active' : '' ?>">
						<a href="<?= \Uri::create('admin/vendor') ?>">Vendors</a>
					</li>
				</ul>
				<ul class="nav navbar-nav pull-right">
					<li class="dropdown">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#"><?php echo $current_user->username ?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><?php echo Html::anchor('admin/logout', 'Logout') ?></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1><?php echo $title; ?></h1>
				<hr>
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
			</div>
			<div class="col-md-12">
<?php echo $content; ?>
			</div>
		</div>
		<hr/>
		<footer>
			<p class="pull-right">Page rendered in {exec_time}s using {mem_usage}mb of memory.</p>
			<p>
				<a href="http://fuelphp.com">FuelPHP</a> is released under the MIT license.<br>
				<small>Version: <?php echo e(Fuel::VERSION); ?></small>
			</p>
		</footer>
	</div>
<?= Asset::js(['admin.js', 'dist/spin.min.js', 'dist/ladda.min.js']); ?>
</body>
</html>
