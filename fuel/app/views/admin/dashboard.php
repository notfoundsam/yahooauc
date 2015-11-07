<div class="alert alert-dismissable ajax" style="display: none;">
	<button type="button" class="close" onclick="$('.alert').hide()">&times;</button>
	<p></p>
</div>

<div class="center-form">
	<label class="control-label">Lot ID:</label> <input class="form-control" id="lot_id" type="text" placeholder="Enter lot ID"><br />
	<label class="control-label">Price:</label> <input class="form-control" id="lot_price" type="text" placeholder="Enter wishful price"><br />
	<button id="form-bid" class="btn btn-primary ladda-button" data-style="zoom-in"><span class="ladda-label">Bid</span></button>
</div>


<div class="jumbotron">
	<?= Uri::create('controller', [], array('redirect' => 'paid')); ?><br />
	<?= Date::forge(strtotime('-1 minutes')) ?><br />
	<?= strtotime('-1 month') ?>

	<p>This admin panel has been generated by the FuelPHP Framework.</p>
	<p><a class="btn btn-primary btn-lg" href="http://docs.fuelphp.com">Read the Docs</a></p>
</div>

<script type="text/javascript">
	
	$('#form-bid').click(function(){ 
		var l = Ladda.create(this);
		l.start();
		var lot_id = $('#lot_id').val();
		var lot_price = $('#lot_price').val();
		$.ajax({
			url: '/admin/api/bid',
			type: 'POST',
			data: {
				csrf_token_key: "<?= \Security::fetch_token();?>",
				lot_id: lot_id,
				lot_price: lot_price
			},
			success: function (data) {
				if (data.error.length == 0){

					if (data.result) {
						$('.ajax p').html(data.result + " auction was won, refresh the page");
						$('.ajax').addClass('alert-success').show();
						$('html, body').animate({scrollTop: '0px'}, 0);
					}
					else {
						$('.ajax p').html("No items was won");
						$('.ajax').addClass('alert-success').show();
						$('html, body').animate({scrollTop: '0px'}, 0);
					}
				}
				else {
					var error_message = '';
					for (i = 0; i < data.error.length; i++) {
						error_message += data.error[i] + "<br>";
					}
					$('.ajax p').html(error_message);
					$('.ajax').addClass('alert-danger').show();
					$('html, body').animate({scrollTop: '0px'}, 0);
				}
				l.stop();
			},
			error: function(){
				l.stop();
				$('.ajax p').html("API error has occurred!");
				$('.ajax').addClass('alert-danger').show();
				$('html, body').animate({scrollTop: '0px'}, 0);
			}
		});
	});
</script>
