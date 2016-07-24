// Refresh button on sort page
$('.create-part-hide .refresh-won').click(function(){ 
	var pages = $(this).closest('.refresh-confirm').find('input[name=page]').val();
	if (!pages.length)
		return;

	var l = Ladda.create(this);
	l.start();
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
					// $('html, body').animate({scrollTop: '0px'}, 0);
				}
				else {
					showAlert("No items was won", 'alert-success');
					// $('html, body').animate({scrollTop: '0px'}, 0);
				}
			}
			else {
				showAlert(data.error, 'alert-danger');
				// $('html, body').animate({scrollTop: '0px'}, 0);
			}
			l.stop();

		},
		error: function(){
			l.stop();
			showAlert("API error has occurred!", 'alert-danger');
			// $('html, body').animate({scrollTop: '0px'}, 0);
		}
	});
	$('.refresh-confirm').hide();
});

$('.create-part-hide .refresh-button').on('click', function(){
	$('.refresh-confirm').show();
});

$('.create-part-hide .cancel').on('click', function(){
	$('.refresh-confirm').hide();
});


// Select all checkboxes on sort page
var sort_checked = false;
$('#select_all').on('click', function() {
	var checkboxes = $('.sort-wrapper').find(':checkbox');

	if(sort_checked) {
		sort_checked = false;
		checkboxes.prop('checked', false);
		$(this).html('Check all');
	} else {
		sort_checked = true;
		checkboxes.prop('checked', true);
		$(this).html('Uncheck all');
	}
});

// Create or add part
$('#create_part').on('click', function(){
	var selected = [];
	var combine_id = $('.create-part-hide input[name=combine_id]').val();
	$('.wrapper input:checked').each(function() {
		selected.push($(this).val());
	});

	if (!selected.length)
		return;

	var l = Ladda.create(this);
	l.start();
	$.ajax({
		url: '/admin/api/createpart',
		type: 'POST',
		data: {
			csrf_token_key: "<?= \Security::fetch_token();?>",
			ids: selected,
			combine_id: combine_id
		},
		success: function (data) {
			l.stop();
			if (!data.error) {
				if (data.result) {
					$.each(selected, function(index, value) {
						$(".wrapper input[value=" + value+ "]").closest('.sort-wrapper').hide("highlight", {color:"#4AE22F"}, 2000, function() {
							$(this).remove();
						});
					});
					showAlert(data.result, 'alert-success');
					$('.create-part-hide input[name=combine_id]').val('')
				}
			}
			else {
				showAlert(data.error, 'alert-danger');
				$.each(selected, function(index, value) {
					$(".wrapper input[value=" + value+ "]").effect("highlight", {color:"#EA4A4A"}, 3000);
				});
			}
		},
		error: function() {
			l.stop();
			showAlert("API error has occurred!", 'alert-danger');
		}
	});
});
