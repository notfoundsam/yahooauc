$(function(){

	// Slideout menu initialaze for device with small screen
	if ($(window).width() < 900)
	{
		var slideout = new Slideout({
			'panel': document.getElementById('panel'),
			'menu': document.getElementById('menu'),
			'padding': 256,
			'tolerance': 70,
			'touch': true
		});
		$('.menu-header button').on('click', function(){
			slideout.toggle();
		});

		// Show submenu by click
		$(".navigation ul ul").each(function(){
			$(this).closest('li').find('a').on('click', function(){
				var sub = $(this).closest('li').find('ul');
				if (sub.is(':visible'))
					sub.hide();
				else
					sub.show();
			});
		});
	}

	// Refresh button on sort page
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
						$('.ajax').removeClass('alert-danger');
						$('.ajax').addClass('alert-success').show();
						$('html, body').animate({scrollTop: '0px'}, 0);
					}
					else {
						$('.ajax p').html("No items was won");
						$('.ajax').removeClass('alert-danger');
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
					$('.ajax').removeClass('alert-success');
					$('.ajax').addClass('alert-danger').show();
					$('html, body').animate({scrollTop: '0px'}, 0);
				}
				l.stop();
			},
			error: function(){
				l.stop();
				$('.ajax p').html("API error has occurred!");
				$('.ajax').removeClass('alert-success');
				$('.ajax').addClass('alert-danger').show();
				$('html, body').animate({scrollTop: '0px'}, 0);
			}
		});
	});
	
	// Select all checkboxes on sort page
	$('#select_all').change(function() {
		
		var checkboxes = $(this).closest('form').find(':checkbox');
		if($(this).is(':checked')) {
			checkboxes.prop('checked', true);
		} else {
			checkboxes.prop('checked', false);
		}
	});

	// Bid button
	$('#form-bid').click(function(){ 
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
				if (data.error.length == 0){

					if (data.result) {
						$('.ajax p').html(data.result);
						$('.ajax').removeClass('alert-danger');
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
					$('.ajax').removeClass('alert-success');
					$('.ajax').addClass('alert-danger').show();
					$('html, body').animate({scrollTop: '0px'}, 0);
				}
				l.stop();
			},
			error: function(){
				l.stop();
				$('.ajax p').html("API error has occurred!");
				$('.ajax').removeClass('alert-success');
				$('.ajax').addClass('alert-danger').show();
				$('html, body').animate({scrollTop: '0px'}, 0);
			}
		});
	});
});