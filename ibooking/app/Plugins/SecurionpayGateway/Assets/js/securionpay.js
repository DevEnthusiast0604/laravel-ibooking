$(function () {
    SecurionpayCheckout.key = gmz_securionpay_params.publicKey;
    SecurionpayCheckout.success = function (result) {
        var checkoutForm = $('#checkout-form'),
            action = $('#securionpay_checkout_request').data('success-action'),
            loader = $('.gmz-loader', checkoutForm),
            message = $('.gmz-message', checkoutForm),
            data = [];

        data.push({
            name: '_result',
            value: JSON.stringify(result)
        },{
            name: '_token',
            value: $('meta[name="csrf-token"]').attr('content')
        },{
            name: '_id',
            value: $('#checkout-form').attr('data-order-id')
        });

        loader.show();

        if(message.length > 0){
            message.empty();
        }

        $.post(action, data, function (respon) {
            if (typeof respon === 'object') {
                if(message.length > 0 && typeof respon.message !== 'undefined'){
                    var classMessage = '';
                    if(respon.status){
                        classMessage = 'alert alert-success';
                    }else{
                        classMessage = 'alert alert-danger';
                    }
                    message.html('<div class="'+ classMessage +'">'+ respon.message +'</div>');
                    loader.hide();
                }
            }

            if (typeof respon.redirect !== 'undefined') {
                setTimeout(function () {
                    window.location.href = respon.redirect;
                });
            }

            if (typeof respon.reload !== 'undefined') {
                setTimeout(function () {
                    window.location.reload();
                }, 1500);
            }
        }, 'json');
    };
    SecurionpayCheckout.error = function (errorMessage) {
        var checkoutForm = $('#checkout-form'),
            action = $('#securionpay_checkout_request').data('error-action'),
            loader = $('.gmz-loader', checkoutForm),
            message = $('.gmz-message', checkoutForm),
            data = [];
        message.html('<div class="alert alert-danger">'+ errorMessage +'</div>');

        data.push({
            name: '_token',
            value: $('meta[name="csrf-token"]').attr('content')
        },{
            name: '_id',
            value: $('#checkout-form').attr('data-order-id')
        });

        loader.show();

        $.post(action, data, function (respon) {
            loader.hide();
        }, 'json');
    };

    $('#checkout-form').on('gmz_form_action_before', function(form){
        var payment = $('input[name="payment_method"]:checked', $('#checkout-form .payment-form')).val();
        if (payment === 'securionpay') {
            SecurionpayCheckout.open({
                checkoutRequest: $('#securionpay_checkout_request').data('checkout-request'),
                name: 'SecurionPay',
                description: 'Checkout'
            });
            return false;
        }
    })
});