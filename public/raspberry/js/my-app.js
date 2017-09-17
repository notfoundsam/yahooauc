// var ajax_host = 'http://yahooauc.dev';
var ajax_host = '';
var current_bidder = '';
// var ajax_host = 'http://yahooauc-servletyahoo.rhcloud.com';

// Initialize app
var myApp = new Framework7({
    //Tell Framework7 to compile templates on app init
    template7Pages: true,
    material: true,

});
 
// If we need to use custom DOM library, let's save it to $$ variable:
var $$ = Dom7;
 
// Add view
var mainView = myApp.addView('.view-main', {
  // Because we want to use dynamic navbar, we need to enable it for this view:
  // dynamicNavbar: true
});

var conn = null;

console.log('START');

$$.ajax({
    url: ajax_host + '/admin/api/check_login',
    type: 'POST',
    success: function (data) {
        var d_obj = JSON.parse(data);
        if (d_obj.status_code == 100) {
            $$('#username').text(d_obj.result.current_user);
            current_bidder = d_obj.result.current_bidder;

            mainView.router.load({
                url: 'status.html'
                // reload: true,
                // context: {
                //  title: 'Bid'
                // }
            });
        }
    },
    statusCode: {
        401: function (xhr) {
            myApp.loginScreen();
        }
    }
});

$$('#bidding').on('click', function() {
    myApp.showIndicator();

    $$.ajax({
        url: ajax_host + '/admin/api/bidding',
        type: 'GET',
        statusCode: {
            401: function(xhr) {
                myApp.hideIndicator();
                myApp.loginScreen();
            }
        },
        success: function(data) {
            var d_obj = JSON.parse(data);
            if (d_obj.status_code == 100) {
                var auctions = d_obj.result;

                mainView.router.load({
                    url: 'bidding.html',
                    reload: (mainView.url == 'bidding.html'),
                    ignoreCache: true,
                    context: {
                        title: 'Bidding',
                        auctions: auctions,
                        current_bidder: current_bidder
                    }
                });
                $$('#bidding_count').text(auctions.length);
            } else {
                console.log(d_obj.message);
            }
            myApp.hideIndicator();
        },
        error: function(data) {
            console.log(data);
            myApp.hideIndicator();
        }
    });
});

$$('#login').on('click', function() {
    var email = $$('input[name=username]').val();
    var password = $$('input[name=password]').val();

    if (!email || !password) {
        myApp.alert('Enter both username and password', 'Input error');
        return;
    }

    $$.ajax({
        url: ajax_host + '/admin/api/login',
        type: 'POST',
        data: {
            email: email,
            password: password
        },
        success: function (data) {
            var d_obj = JSON.parse(data);
            switch (d_obj.status_code) {
                case 10: 
                    $$('#username').text(d_obj.result.current_user);
                    current_bidder = d_obj.result.current_bidder;
                    mainView.router.load({
                        url: 'status.html'
                    });
                    myApp.closeModal('.login-screen');
                    break;
                case 20: 
                    myApp.closeModal('.login-screen');
                    break;
                case 30:
                    myApp.alert('Login or password incorect', 'Login error');
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

myApp.onPageInit('bid', function (page) {
    $$('#bid').on('click', function() {

        var auc_id = $$('input[name=auc_id]').val();
        var price = $$('input[name=price]').val();

        if (!auc_id || !price) {
            myApp.alert('Enter both Lot ID and Price', 'Input error');
            return;
        }

        myApp.showIndicator();

        bid(auc_id, price, false);
    });
});

myApp.onPageInit('status', function (page) {
    console.log("Activate");
    activateConnection();
    // $$('.bid-up').on('click', function() {

    //     var auc_id = $$(this).attr('data-id');

    //     myApp.prompt('Lot ID: ' + auc_id, 'Set new price', function (value) {
    //         var price = parseInt(value);
    //         if (!price) {
    //             return;
    //         }
            
    //         myApp.showIndicator();

    //         bid(auc_id, price, true);
    //     });
    // });
});

$$(document).on('pageInit', function (e) {
    // if (e.detail.page.name === 'devices') {
    //     getTemperature();
    // }

    $$('.button-control').on('click', function() {
        var request = {};
        request.device = $$(this).closest('.page').attr('data-page');
        request.command = $$(this).attr('data-command');

        conn.send(JSON.stringify(request));



        // $$.ajax({
        //     url: '/api/v1.0/device/' + name + '/' + command,
        //     type: 'GET',
        //     success: function (data) {
        //         var d_obj = JSON.parse(data);
        //         if (d_obj.status_code != 100) {
        //             myApp.addNotification({
        //                 message: 'Programm error',
        //                 hold: 3000
        //             });
        //         }
        //     }
        //     // statusCode: {
        //     //  401: function (xhr) {
        //     //      myApp.loginScreen();
        //     //  }
        //     // }
        // });
    });
});

function bid(auc_id, price, rebid) {
    $$.ajax({
        url: ajax_host + '/admin/api/bid',
        type: 'POST',
        data: {
            auc_id: auc_id,
            price: price
        },
        statusCode: {
            401: function (xhr) {
                myApp.hideIndicator();
                myApp.loginScreen();
            }
        },
        success: function (data) {
            var d_obj = JSON.parse(data);
            if (d_obj.status_code == 100) {

                if (rebid) {
                    var card = $$('a[data-id=' + auc_id + ']').closest('.card');
                    card.removeClass('card-red').addClass('card-green');
                }

                myApp.addNotification({
                    message: d_obj.result,
                    hold: 3000,
                    additionalClass: 'bid-success'
                });
            } else {
                myApp.addNotification({
                    message: d_obj.error,
                    hold: 3000,
                    additionalClass: 'bid-error'
                });
            }
            myApp.hideIndicator();
        }
    });
}

function activateConnection() {
    if (conn == null) {
        conn = new WebSocket('ws://localhost:9090/?key=web_secret_key');
    }
    conn.onopen = function(e) {
        console.log("Connection established!");
    };

    conn.onmessage = function(e) {
        console.log(e.data);
    };
}
