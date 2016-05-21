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

	// Auction item edit popup window close button
	$('#item-edit-popup .close').on('click', function() {
		$('#item-edit-popup').hide();
	});

	// Auction item edit popup button
	$('#item-edit-popup button').click(function(){
		var popup = $('#item-edit-popup');
		var item_id = popup.find('#item-id').val();
		var count = popup.find('#count').val();
		var price = popup.find('#price').val();
		var comment = popup.find('#comment').val();
		var edit_item = $(".item-wrapper[item-id='" + item_id + "']");

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
				if (!data.error) {
					if (data.result) {
						edit_item.find('.count').text(count);
						edit_item.find('.price').text(price);
						edit_item.find('.comment').text(comment);
						if (comment)
						{
							edit_item.find('.fa-comment').removeClass('hidden');
						}
						else
						{
							edit_item.find('.fa-comment').addClass('hidden');
						}
						showAlert(data.result, 'alert-success');
						edit_item.effect("highlight", {color:"#4AE22F"}, 3000);
					}
				}
				else {
					showAlert(data.error, 'alert-danger');
					edit_item.effect("highlight", {color:"#EA4A4A"}, 3000);
				}
			},
			error: function() {
				l.stop();
				popup.hide();
				showAlert("API error has occurred!", 'alert-danger');
			}
		});
	});

	// Auction item edit button
	$('.auc-edit-button').each(function() {
		$(this).on('click', function() {
			var item = $(this).closest('.item-wrapper');
			var popup = $('#item-edit-popup');
			popup.find('h2').text(item.find('.title').text());
			popup.find('#count').val(item.find('.count').text());
			popup.find('#price').val(item.find('.price').text());
			popup.find('#comment').val(item.find('.comment').text());
			popup.find('#item-id').val(item.attr('item-id'));
			popup.show();
		});
	});

	// Auction item delete popup window close button
	$('#item-delete-popup .close').on('click', function() {
		$('#item-delete-popup').hide();
	});

	// Auction item popup delete button
	$('#item-delete-popup button').click(function(){
		var popup = $('#item-delete-popup');
		var item_id = popup.find('#item-id').val();
		var edit_item = $(".item-wrapper[item-id='" + item_id + "']");

		var l = Ladda.create(this);
		l.start();
		$.ajax({
			url: '/admin/api/deleteauc',
			type: 'POST',
			data: {
				csrf_token_key: "<?= \Security::fetch_token();?>",
				id: item_id,
			},
			success: function (data) {
				l.stop();
				popup.hide();
				if (!data.error) {
					if (data.result) {
						
						showAlert(data.result, 'alert-success');
						edit_item.hide("highlight", {color:"#4AE22F"}, 2000, function() {
							$(this).remove();
						});
					}
				}
				else {
					showAlert(data.error, 'alert-danger');
					edit_item.effect("highlight", {color:"#EA4A4A"}, 3000);
				}
			},
			error: function() {
				l.stop();
				popup.hide();
				showAlert("API error has occurred!", 'alert-danger');
			}
		});
	});

	// Auction item delete button
	$('.auc-delete-button').each(function() {
		$(this).on('click', function() {
			var item = $(this).closest('.item-wrapper');
			var popup = $('#item-delete-popup');
			popup.find('h2').text(item.find('.title').text());
			popup.find('#item-id').val(item.attr('item-id'));
			popup.show();
		});
	});

});

function showAlert(message, class) {
	$('#alert p').html(message);
	$('#alert').removeClass()
	.addClass(class);
}