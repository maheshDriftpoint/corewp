<?php 
function ajax_login() {
    // Check nonce for security
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'ajax-login-nonce')) {
        echo json_encode(array('success' => false, 'message' => 'Nonce verification failed.'));
        die();
    }

    // Get the username and password
    $username = sanitize_text_field($_POST['username']);
    $password = sanitize_text_field($_POST['password']);

    // Try to log the user in
    $creds = array(
        'user_login'    => $username,
        'user_password' => $password,
        'remember'      => true
    );
    $user = wp_signon($creds, false);

    // If login is successful
    if (is_wp_error($user)) {
        $error_message = $user->get_error_message();
        echo json_encode(array('success' => false, 'message' => $error_message));
    } else {
        echo json_encode(array(
            'success' => true,
            'message' => 'Login successful!',
            'redirect_url' => home_url() // Redirect after successful login
        ));
    }

    die();
}
add_action('wp_ajax_nopriv_ajax_login', 'ajax_login'); // For not logged-in users
add_action('wp_ajax_ajax_login', 'ajax_login'); // For logged-in users


function ajax_register() {
    echo '<pre>';
    print_r($_POST); die; 
    
    // Check nonce for security
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'ajax-register-nonce')) {
        echo json_encode(array('success' => false, 'message' => 'Nonce verification failed.'));
        die();
    }

    // Get the form data
    $username = sanitize_text_field($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];

    // Validate form data
    if (empty($username) || empty($email) || empty($password)) {
        echo json_encode(array('success' => false, 'message' => 'Please fill in all the fields.'));
        die();
    }

    // Check if the username or email already exists
    if (username_exists($username)) {
        echo json_encode(array('success' => false, 'message' => 'Username already exists.'));
        die();
    }

    if (email_exists($email)) {
        echo json_encode(array('success' => false, 'message' => 'Email already registered.'));
        die();
    }

    // Create the user
    $user_id = wp_create_user($username, $password, $email);
    if (is_wp_error($user_id)) {
        echo json_encode(array('success' => false, 'message' => 'Error creating user.'));
        die();
    }

    // Set user role to "subscriber" (default role)
    $user = new WP_User($user_id);
    $user->set_role('subscriber');

    // Send success response
    echo json_encode(array(
        'success' => true,
        'message' => 'Registration successful!',
        'redirect_url' => home_url() // Redirect after successful registration
    ));

    die();
}
add_action('wp_ajax_nopriv_ajax_register', 'ajax_register'); // For not logged-in users
add_action('wp_ajax_ajax_register', 'ajax_register'); // For logged-in users
