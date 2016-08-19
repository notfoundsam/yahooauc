<div id="menu" class="navigation">
	<ul>
		<li class="<?php echo Uri::segment(2) == '' ? 'active' : '' ?>">
		<?php echo Html::anchor('admin', 'Dashboard') ?>
		</li>
		<li class="<?php echo Uri::segment(2) == 'bidding' ? 'active' : '' ?>">
		<a href="<?= \Uri::create('admin/bidding') ?>">Bidding</a>
		</li>
		<li class="<?= in_array(\Uri::segment(2), ['pay', 'paid', 'received']) ? 'active' : '' ?>">
		<a href="#">Won</b>
		</a>
			<ul>
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
			<a href="<?= \Uri::create('admin/ship') ?>">Ship</a>
			</li>
			<li>
			<a href="<?= \Uri::create('admin/sell') ?>">Sell</a>
			</li>
			</ul>
		</li>
		<li class="<?php echo Uri::segment(2) == 'shipped' ? 'active' : '' ?>">
		<a href="<?= \Uri::create('admin/shipped') ?>">Shipped</a>
		</li>
	<?php if( Auth::member(\Config::get('my.groups.superadmin')) ) : ?>
		<li class="<?php echo Uri::segment(2) == 'sort' ? 'active' : '' ?>">
		<a href="<?= \Uri::create('admin/sort') ?>">Sort</a>
		</li>
	<?php endif; ?>
		<li class="<?php echo Uri::segment(2) == 'vendor' ? 'active' : '' ?>">
		<a href="<?= \Uri::create('admin/vendor') ?>">Vendors</a>
		</li>
		<li class="<?php echo Uri::segment(2) == 'finance' ? 'active' : '' ?>">
		<a href="<?= \Uri::create('admin/finance') ?>">Finances</a>
		</li>
		<li class="<?php echo Uri::segment(2) == 'statistic' ? 'active' : '' ?>">
		<a href="<?= \Uri::create('admin/statistic') ?>">Statistic</a>
		</li>
	</ul>
	<ul>
		<li>
			<a href="#"><?php echo $current_user->username ?></a>
			<ul>
				<li><?php echo Html::anchor('admin/logout', 'Logout') ?></li>
			</ul>
		</li>
	</ul>
</div>
