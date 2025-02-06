jQuery(document).ready(function($) {
    //login 
    $('#ajax-login-form').on('submit', function(e) {
        e.preventDefault(); // Prevent form from submitting normally

        var username = $('#username').val();
        var password = $('#password').val();
        var security = ajaxlogin.security;

        // Send AJAX request
        $.ajax({
            url: ajaxlogin.ajaxurl,
            method: 'POST',
            data: {
                action: 'ajax_login', // The action hook
                username: username,
                password: password,
                security: security
            },
            success: function(response) {
                if (response.success) {
                    // Redirect or show success message
                    $('#login-result').html('<div class="alert alert-success">' + response.data.message + '</div>');
                    setTimeout(function() {
                        window.location.href = response.data.redirect_url; // Redirect after login
                    }, 2000);
                } else {
                    $('#login-result').html('<div class="alert alert-danger">' + response.data.message + '</div>');
                }
            },
            error: function() {
                $('#login-result').html('<div class="alert alert-danger">Something went wrong. Please try again later.</div>');
            }
        });
    });

    //register 
    $('#ajax-register-form').on('submit', function(e) {
        e.preventDefault(); 

        var username = $('#username').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var security = ajaxregister.security;

        console.log('username Data: ' +username); 
        console.log('email Data: ' +email); 
        console.log('password Data: ' +password); 

        // Send AJAX request
        $.ajax({
            url: ajaxlogin.ajaxurl,
            method: 'POST',
            data: {
                action: 'ajax_register', 
                username: username,
                email: email,
                password: password,
                security: security
            },
            success: function(response) {
                if (response.success) {
                    // Display success message
                    $('#register-result').html('<div class="alert alert-success">' + response.data.message + '</div>');
                    setTimeout(function() {
                        window.location.href = response.data.redirect_url; // Redirect after registration
                    }, 2000);
                } else {
                    $('#register-result').html('<div class="alert alert-danger">' + response.data.message + '</div>');
                }
            },
            error: function() {
                $('#register-result').html('<div class="alert alert-danger">Something went wrong. Please try again later.</div>');
            }
        });
    });
    
});


