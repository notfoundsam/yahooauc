$('.overlay').on('click', function(e) {
	if (e.target !== this)
		return;
	$(this).hide();
});	

function showAlert(message, class)
{
	$('#alert p').html(message);
	$('#alert').removeClass().addClass(class);
}
