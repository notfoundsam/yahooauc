// var ajax_host = 'http://yahooauc.dev';
var ajax_host = '';
// var ajax_host = 'http://yahooauc-servletyahoo.rhcloud.com';

// Initialize app
var myApp = new Framework7();
 
// If we need to use custom DOM library, let's save it to $$ variable:
var $$ = Dom7;
 
// Add view
var mainView = myApp.addView('.view-main', {
  // Because we want to use dynamic navbar, we need to enable it for this view:
  // dynamicNavbar: true
});

console.log('START');

$$.ajax({
	url: ajax_host + '/admin/api/check_login',
	type: 'POST',
	// success: function () {
	// 	mainView.router.load({url: 'bid.html'});
	// },
	statusCode: {
		401: function (xhr) {
			myApp.loginScreen();
		}
	}
});

$$('#login').on('click', function() {
	$$.ajax({
		url: ajax_host + '/admin/api/login',
		type: 'POST',
		data: {
			email: $$('input[name=username]').val(),
			password: $$('input[name=password]').val()
		},
		success: function (data) {
			var d_obj = JSON.parse(data);
			switch (d_obj.status_code) {
				case 10: 
					myApp.closeModal('.login-screen');
					// mainView.router.load({url: 'bid.html'});
					break;
				case 20: 
					myApp.closeModal('.login-screen');
					// mainView.router.load({url: 'bid.html'});
					break;
				case 30:
					console.log('wrong');
					break;
			}
		}
	});
});

$$('#logout').on('click', function() {
	myApp.closePanel();
	$$.ajax({
		url: ajax_host + '/admin/api/logout',
		type: 'POST',
		success: function (data) {
			var d_obj = JSON.parse(data);
			console.log(d_obj);
			if (d_obj.status_code == 40) {
				myApp.loginScreen();
			}
		}
	});
});
//tets

// Callbacks to run specific code for specific pages, for example for About page:
// myApp.onPageInit('about', function (page) {
//     // run createContentPage func after link was clicked
//     $$('.create-page').on('click', function () {
//         createContentPage();
//     });
// });
$$(document).on('pageInit', function (e) {
  // Do something here when page loaded and initialized
  
})
