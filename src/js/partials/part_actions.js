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
	$('body').removeClass('freeze');
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

// Part comment button
$('.part-wrapper .fa-comment').each(function() {
	$(this).on('click', function() {
		var part = $(this).closest('.part-wrapper');
		var popup = $('#comment-popup');
		popup.find('h2').text(part.find('.comment').text());
		popup.show();
	});
});
