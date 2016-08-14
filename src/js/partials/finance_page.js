// Show popup
$('.add-finance .add-button').on('click', function(){
	$('body').addClass('freeze');
	$('#finance_popup').show();
});

// Close popup
$('#finance_popup .close').on('click', function() {
	$('body').removeClass('freeze');
	$('#finance_popup').hide();
});

// Add new vendor update button
$('#finance_popup button').click(function(){
	var popup = $('#finance_popup');
	var finance_usd = popup.find('#finance_usd').val();
	var finance_jpy = popup.find('#finance_jpy').val();
	var comment = popup.find('#comment').val();

	var l = Ladda.create(this);
	l.start();
	$.ajax({
		url: '/admin/api/addfinance',
		type: 'POST',
		data: {
			csrf_token_key: "<?= \Security::fetch_token();?>",
			finance_usd: finance_usd,
			finance_jpy: finance_jpy,
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
