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
	$('.sort-form button').click(function(){ 
		var l = Ladda.create(this);
		l.start();
		var pages = $(this).parent().find('input').val();
		$.ajax({
			url: '/admin/api/refresh',
			type: 'POST',
			data: {
				csrf_token_key: "<?= \Security::fetch_token();?>",
				pages: pages
			},
			success: function (data) {
				if (!data.error){

					if (data.result) {
						showAlert(data.result + " auction was won, refresh the page", 'alert-success');
						$('html, body').animate({scrollTop: '0px'}, 0);
					}
					else {
						showAlert("No items was won", 'alert-success');
						$('html, body').animate({scrollTop: '0px'}, 0);
					}
				}
				else {
					showAlert(data.error, 'alert-danger');
					$('html, body').animate({scrollTop: '0px'}, 0);
				}
				l.stop();
			},
			error: function(){
				l.stop();
				showAlert("API error has occurred!", 'alert-danger');
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

	// Auction item popup window close button
	$('#auc-item-popup .close').on('click', function() {
		$('#auc-item-popup').hide();
		$('body').css('overflow','auto');
	});

	// Auction item popup update button
	$('#auc-item-popup button').click(function(){
		var popup = $('#auc-item-popup');
		var item_id = popup.find('#item-id').val();
		var count = popup.find('#count').val();
		var price = popup.find('#price').val();
		var comment = popup.find('#comment').val();

		var l = Ladda.create(this);
		l.start();
		$.ajax({
			url: '/admin/api/updateauc',
			type: 'POST',
			data: {
				csrf_token_key: "<?= \Security::fetch_token();?>",
				id: item_id,
				count: count,
				price: price,
				comment: comment
			},
			success: function (data) {
				l.stop();
				popup.hide();
				$('body').css('overflow','auto');
				if (!data.error) {
						console.log('OK');

					if (data.result) {
						showAlert(data.result, 'alert-success');
					}
				}
				else {
					console.log('OPPS');
					showAlert(data.error, 'alert-danger');
				}
			},
			error: function() {
				l.stop();
				console.log('FAIL');
				popup.hide();
				$('body').css('overflow','auto');
				showAlert("API error has occurred!", 'alert-danger');
			}
		});
	});

	// Auction item edit button
	$('.auc-edit-button').each(function() {
		$(this).on('click', function() {
			var item = $(this).closest('.item-wrapper');
			var popup = $('#auc-item-popup');
			popup.find('h2').text(item.find('.title').text());
			popup.find('#count').val(item.find('.count').text());
			popup.find('#price').val(item.find('.price').text());
			popup.find('#item-id').val(item.attr('item-id'));
			// var auc_id = $(this).closest('.item-wrapper').attr('auc-id');
			// console.log(auc_id);
			$('body').css('overflow','hidden')
			popup.show();
		});
	});

});

function showAlert(message, class) {
	$('#alert p').html(message);
	$('#alert').removeClass()
	.addClass(class);
}