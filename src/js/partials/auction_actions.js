// Auction item edit popup window close button
$('#item-edit-popup .close').on('click', function() {
	$('body').removeClass('freeze');
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
	$('body').removeClass('freeze');
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

// Item comment button
$('.item-wrapper .fa-comment').each(function() {
	$(this).on('click', function() {
		var item = $(this).closest('.item-wrapper');
		var popup = $('#comment-popup');
		popup.find('h2').text(item.find('.comment').text());
		popup.show();
	});
});
