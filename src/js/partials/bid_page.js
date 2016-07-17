$(function(){

	// Bid button
	$('.bid-form button').click(function(){ 
		var l = Ladda.create(this);
		l.start();
		var auc_id = $('#auc_id').val();
		var price = $('#price').val();
		$.ajax({
			url: '/admin/api/bid',
			type: 'POST',
			data: {
				csrf_token_key: "<?= \Security::fetch_token();?>",
				auc_id: auc_id,
				price: price
			},
			success: function (data) {
				if (!data.error){

					if (data.result) {
						showAlert(data.result, 'alert-success');
					}
				}
				else {
					showAlert(data.error, 'alert-danger');
				}
				l.stop();
			},
			error: function() {
				l.stop();
				showAlert("API error has occurred!", 'alert-danger');
			}
		});
	});
});
