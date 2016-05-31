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
		var conteiner_wrapper = edit_item.closest('.conteiner-wrapper');

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
						var newCountItem = 0;
						var newPriceItem = 0;
						edit_item.find('.count').text(count);
						edit_item.find('.price').text(price);
						edit_item.find('.comment').text(comment);

						// count items after update
						conteiner_wrapper.find('.item-wrapper .count').each(function() {
							newCountItem += parseInt($(this).text())
						});
						conteiner_wrapper.find('.part-wrapper .count').text(newCountItem);

						// count price after update
						conteiner_wrapper.find('.item-wrapper .price').each(function() {
							newPriceItem += parseInt($(this).text())
						});
						conteiner_wrapper.find('.part-wrapper .items-price span').text(newPriceItem);
						var ship_price = conteiner_wrapper.find('.part-wrapper .ship span').text();
						conteiner_wrapper.find('.part-wrapper .total-price span')
						.text(newPriceItem + parseInt(ship_price));

						if (comment)
						{
							edit_item.find('.fa-comment').removeClass('hidden');
						}
						else
						{
							edit_item.find('.fa-comment').addClass('hidden');
						}
						showAlert(data.result, 'alert-success');
						conteiner_wrapper.find('.part-wrapper').effect("highlight", {color:"#215F20"}, 3000);
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
			$('body').addClass('freeze');
			popup.show();
		});
	});

	// Auction item delete popup window close button
	$('#item-delete-popup .close').on('click', function() {
		$('body').removeClass('freeze');
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

	// Auction part edit popup window close button
	$('#part-edit-popup .close').on('click', function() {
		$('body').removeClass('freeze');
		$('#part-edit-popup').hide();
	});

	// Auction part edit popup button
	$('#part-edit-popup button').click(function(){
		var popup = $('#part-edit-popup');
		var part_id = popup.find('#part-id').val();
		var part_status = popup.find('input[name=status]:checked').val();
		var price = popup.find('#part-ship').val();
		var tracking = popup.find('#part-tracking').val();
		var box = popup.find('#part-box').val();
		var comment = popup.find('#comment').val();
		var edit_item = $(".part-wrapper[part-id='" + part_id + "']");

		var l = Ladda.create(this);
		l.start();
		$.ajax({
			url: '/admin/api/updatepart',
			type: 'POST',
			data: {
				csrf_token_key: "<?= \Security::fetch_token();?>",
				id: part_id,
				status: part_status,
				price: price,
				tracking: tracking,
				box: box,
				comment: comment
			},
			success: function (data) {
				l.stop();
				popup.hide();
				if (!data.error) {
					if (data.result) {
						edit_item.find('.tracking span').text(tracking);
						edit_item.find('.box span').text(box);
						edit_item.find('.ship span').text(price);
						edit_item.find('.comment').text(comment);

						// Update total price
						var items_price = edit_item.find('.items-price span').text();
						edit_item.find('.total-price span')
						.text(parseInt(price) + parseInt(items_price));

						if (comment)
						{
							edit_item.find('.fa-comment').removeClass('hidden');
						}
						else
						{
							edit_item.find('.fa-comment').addClass('hidden');
						}

						showAlert(data.result, 'alert-success');

						if (edit_item.attr('part-status') == part_status)
						{
							edit_item.effect("highlight", {color:"#215F20"}, 3000);
						}
						else 
						{
							edit_item.closest('.conteiner-wrapper').hide("highlight", {color:"#FFFFFF"}, 2000, function() {
								$(this).remove();
							});
						}
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

	// Auction part edit button
	$('.part-edit-button').each(function() {
		$(this).on('click', function() {
			var part = $(this).closest('.part-wrapper');
			var popup = $('#part-edit-popup');
			popup.find('h2').text('Part ID: ' + part.attr('part-id'));
			popup.find('#part-ship').val(part.find('.ship span').text());
			popup.find('#part-tracking').val(part.find('.tracking span').text());
			popup.find('#part-box').val(part.find('.box span').text());
			popup.find('#comment').val(part.find('.comment').text());
			popup.find('#part-id').val(part.attr('part-id'));
			popup.find('#radio_' + part.attr('part-status')).prop('checked', true);
			$('body').addClass('freeze');
			popup.show();
		});
	});

	// Auction part delete popup window close button
	$('#part-delete-popup .close').on('click', function() {
		$('#part-delete-popup').hide();
	});

	// Auction part popup delete button
	$('#part-delete-popup button').click(function(){
		var popup = $('#part-delete-popup');
		var part_id = popup.find('#part-id').val();
		var edit_part = $(".part-wrapper[part-id='" + part_id + "']");

		var l = Ladda.create(this);
		l.start();
		$.ajax({
			url: '/admin/api/deletepart',
			type: 'POST',
			data: {
				csrf_token_key: "<?= \Security::fetch_token();?>",
				id: part_id,
			},
			success: function (data) {
				l.stop();
				popup.hide();
				if (!data.error) {
					if (data.result) {
						
						showAlert(data.result, 'alert-success');
						edit_part.closest('.conteiner-wrapper').hide("highlight", {color:"#FFFFFF"}, 2000, function() {
							$(this).remove();
						});
					}
				}
				else {
					showAlert(data.error, 'alert-danger');
					edit_part.closest('.conteiner-wrapper').find('.item-wrapper, .part-wrapper, .head-wrapper')
					.effect("highlight", {color:"#FFFFFF"}, 3000);
				}
			},
			error: function() {
				l.stop();
				popup.hide();
				showAlert("API error has occurred!", 'alert-danger');
			}
		});
	});

	// Auction part delete button
	$('.part-delete-button').each(function() {
		$(this).on('click', function() {
			var part = $(this).closest('.part-wrapper');
			var popup = $('#part-delete-popup');
			popup.find('h2 span').text(part.attr('part-id'));
			popup.find('#part-id').val(part.attr('part-id'));
			popup.show();
		});
	});

	// Auction part delete popup window close button
	$('#comment-popup .close').on('click', function() {
		$('#comment-popup').hide();
	});

	// Item comment button
	$('.item-wrapper .fa-comment').each(function() {
		$(this).on('click', function() {
			var item = $(this).closest('.item-wrapper');
			var popup = $('#comment-popup');
			popup.find('h2').text(item.find('.comment').text());
			popup.show();
		});
	});

	// Part comment button
	$('.part-wrapper .fa-comment').each(function() {
		$(this).on('click', function() {
			var part = $(this).closest('.part-wrapper');
			var popup = $('#comment-popup');
			popup.find('h2').text(part.find('.comment').text());
			popup.show();
		});
	});

});

function showAlert(message, class) {
	$('#alert p').html(message);
	$('#alert').removeClass()
	.addClass(class);
}