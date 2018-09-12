<div class="bid-form">
	<label class="control-label">Lot ID:</label>
	<input class="form-control" id="bid_id" type="text" placeholder="Enter lot's ID"><br />
	<label class="control-label">Price:</label>
	<input class="form-control" id="bid_price" type="text" placeholder="Enter wishful price"><br />

	<button class="ladda-button" data-style="zoom-in" data-size="s" data-color="blue">
		<span class="ladda-label">Bid</span>
	</button>
</div>

<div style="width: 500px; margin: auto;">
<?php if( Auth::member(\Config::get('my.groups.superadmin')) ) : ?>
    <p>
    <?php foreach(\Model_Login::find('all', ['limit' => 50]) as $login): ?>
        <span style="color: <?= $login->result == 'fail' ? 'red' : 'green'; ?>;"><?= $login->result.' : '.$login->username.' : '.$login->ip.' : '.$login->created_at; ?><span><br>
    <?php endforeach; ?>
    </p>
<?php endif; ?>
</div>
