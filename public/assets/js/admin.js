
	$('#form-refresh').click(function(){ 
		var l = Ladda.create(this);
		l.start();
		var pages = $('#pages').val();
		$.ajax({
			url: '/admin/api/refresh',
			type: 'POST',
			data: {
				csrf_token_key: "<?= \Security::fetch_token();?>",
				pages: pages
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
