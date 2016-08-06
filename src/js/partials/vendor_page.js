// Show popup
$('.add-vendor .add-button').on('click', function(){
	var popup = $('#vendor_popup');
	popup.find('h2').text('Add vendor');
	popup.find('.send-button span').text('Add');
	popup.find('#vendor_name').val('');
	popup.find('#post_index').val('');
	popup.find('#address').val('');
	popup.find('#vendor_id').val('');
	popup.find('#comment').val('');
	popup.find('#radio_0').prop('checked', true);
	popup.show();
});

// Close popup
$('#vendor_popup .close').on('click', function() {
	$('body').removeClass('freeze');
	$('#vendor_popup').hide();
});

// Add new vendor update button
$('#vendor_popup button').click(function(){
	var popup = $('#vendor_popup');
	var vendor_id = popup.find('#vendor_id').val();
	var vendor_name = popup.find('#vendor_name').val();
	var post_index = popup.find('#post_index').val();
	var address = popup.find('#address').val();
	var by_now = popup.find('input[name=by_now]:checked').val();
	var comment = popup.find('#comment').val();

	var l = Ladda.create(this);
	l.start();
	$.ajax({
		url: '/admin/api/addvendor',
		type: 'POST',
		data: {
			csrf_token_key: "<?= \Security::fetch_token();?>",
			vendor_id: vendor_id,
			vendor_name: vendor_name,
			post_index: post_index,
			address: address,
			by_now: by_now,
			comment: comment
		},
		success: function (data) {
			l.stop();
			popup.hide();
			if (!data.error) {
				if (data.result) {
					showAlert(data.result, 'alert-success');
				}
			}
			else {
				showAlert(data.error, 'alert-danger');
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

// Vendor item edit button
$('.vendor-edit-button').each(function() {
	$(this).on('click', function() {
		var item = $(this).closest('.vendor-wrapper');
		var popup = $('#vendor_popup');
		popup.find('h2').text('Edit vendor');
		popup.find('.send-button span').text('Update');
		popup.find('#vendor_name').val(item.find('.vendor-name').text());
		popup.find('#post_index').val(item.find('.post-index').text());
		popup.find('#address').val(item.find('.address').text());
		popup.find('#vendor_id').val(item.attr('vendor-id'));
		popup.find('#comment').val(item.attr('data-memo'));
		popup.find('#radio_' + item.attr('data-by-now')).prop('checked', true);
		$('body').addClass('freeze');
		popup.show();
	});
});

// Vendor comment button
$('.vendor-wrapper .fa-comment').each(function() {
	$(this).on('click', function() {
		var item = $(this).closest('.vendor-wrapper');
		var popup = $('#comment-popup');
		popup.find('h2').text(item.attr('data-memo'));
		popup.show();
	});
});
