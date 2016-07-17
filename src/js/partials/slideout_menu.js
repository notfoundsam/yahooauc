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
