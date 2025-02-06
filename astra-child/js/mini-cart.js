jQuery(document).ready(function ($) {
    $('#cart-icon').on('click', function (e) {
        e.preventDefault();
        $('#mini-cart-container').toggleClass('active');
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#cart-icon, #mini-cart-container').length) {
            $('#mini-cart-container').removeClass('active');
        }
    });

    $('#sidecart-close').on('click', function () {
        $('#mini-cart-container').removeClass('active');
    });

    $('.single_add_to_cart_button').on('click', function (e) {
        e.preventDefault();
        $('#mini-cart-container').addClass('active');

        const productId = $('.variation_id').val() || $('input[name="add-to-cart"]').val();
        const productQuantity = $('input[name="quantity"]').val() || 1;

        mini_cart_handle(productId, productQuantity);
    });

    $('.update-cart').click(function(e) {
        e.preventDefault();
        var cartKey = $(this).data('cart-key');
        var newQty = $(this).data('qty');

        if (newQty < 1) {
            return; 
        }

        //mini_cart_handle(cartKey, newQty); 
        
    });

    function mini_cart_handle(productId, productQuantity) {
        $.ajax({
            url: MiniCartAjax.ajax_url,
            type: 'POST',
            data: {
                action: 'update_mini_cart',
                security: MiniCartAjax.nonce,
                product_id: productId,
                quantity: productQuantity,
            },
            success: function (response) {
                if (response.success) {
                    $('#mini-cart-container').html(response.data.mini_cart);
                    $('#cart-count').text(response.data.cart_count);
                } else {
                    console.error('Error:', response.data);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', status, error);
            }
        });
    }
});

