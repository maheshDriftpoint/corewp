<?php

include('include/theme-functions.php');
include('include/course-functions.php');
include('include/customize-options.php');
include('include/mini-cart-functions.php');
include('include/drog-drop-contact.php');

// Enqueue Parent Theme Styles
function astra_child_enqueue_styles() {
    // Enqueue the parent theme stylesheet
    wp_enqueue_style('astra-parent-style', get_template_directory_uri() . '/style.css');

    // Enqueue the child theme stylesheet
    wp_enqueue_style('astra-child-style', get_stylesheet_directory_uri() . '/style.css', array('astra-parent-style'), wp_get_theme()->get('Version'));
    wp_enqueue_style('astra-child-custom-style', get_stylesheet_directory_uri() . '/css/custom.css');
}
add_action('wp_enqueue_scripts', 'astra_child_enqueue_styles');

function custom_login_enqueue_scripts() {
  //wp_enqueue_script('custom-script', get_stylesheet_directory_uri() . '/js/custom-script.js', array('jquery'), null, true);

  $random_version = rand(100, 999);
  // Enqueue the script with the random version number
  wp_enqueue_script('custom-script',  get_stylesheet_directory_uri() . '/js/custom-script.js', array('jquery'), $random_version, true  );

  wp_localize_script('custom-script', 'ajaxlogin', array(
      'ajaxurl' => admin_url('admin-ajax.php'),
      'security' => wp_create_nonce('ajax-login-nonce')
  ));
}
add_action('wp_enqueue_scripts', 'custom_login_enqueue_scripts');


/* function enqueue_category_filter_scripts() {
  //wp_enqueue_script('category-filter', get_stylesheet_directory_uri() . '/js/category-filter.js', ['jquery'], null, true);

  wp_localize_script('category-filter', 'ajax_params', [
      'ajax_url' => admin_url('admin-ajax.php'),
  ]);
}
add_action('wp_enqueue_scripts', 'enqueue_category_filter_scripts'); */


include('include/shop-functions.php');

if (isset($_GET['test_email'])) {
    add_action('wp_head', 'test_email_sending');
}

function test_email_sending() {
    // Set email details
    $to = 'sereli5161@evusd.com'; //Replace with your email address
    $subject = 'Test Email from WordPress';
    $message = 'This is a test email sent from your WordPress site.';
    $headers = array('Content-Type: text/html; charset=UTF-8');

    // Send the email
    if (wp_mail($to, $subject, $message, $headers)) {
        echo 'Email sent successfully!';
    } else {
        echo 'Failed to send email.';
    }
}


// Add custom meta field to product
function add_product_information_field() {
  global $post;

  echo '<div class="options_group">';

  woocommerce_wp_text_input([
      'id' => '_product_information',
      'label' => __('Product Information', 'woocommerce'),
      'placeholder' => 'Enter additional product information',
      'desc_tip' => true,
      'description' => __('This field allows you to add extra product details.', 'woocommerce'),
      'type' => 'text',
  ]);

  echo '</div>';
}
add_action('woocommerce_product_options_general_product_data', 'add_product_information_field');

// Save the custom meta field value
function save_product_information_field($post_id) {
  $product_information = isset($_POST['_product_information']) ? sanitize_text_field($_POST['_product_information']) : '';
  update_post_meta($post_id, '_product_information', $product_information);
}
add_action('woocommerce_process_product_meta', 'save_product_information_field');

// Display custom meta field value on the front-end
function display_product_information_field() {
  global $post;

  $product_information = get_post_meta($post->ID, '_product_information', true);

  if ($product_information) {
      echo '<p class="product-information"><strong>' . __('Product Information:', 'woocommerce') . '</strong> ' . esc_html($product_information) . '</p>';
  }
}
add_action('woocommerce_single_product_summary', 'display_product_information_field', 20);


// Replace order product name with _product_information during checkout
function replace_product_name_with_product_information($item, $cart_item_key, $values, $order) {
  // Get the product object
  $product = $values['data'];

  if ($product) {
      // Retrieve the custom field _product_information
      $product_information = get_post_meta($product->get_id(), '_product_information', true);


      // If _product_information exists, replace the product name
      if (!empty($product_information)) {
          $item->set_name($product_information); // Replace the product name in the order item
      }
  }
}
add_action('woocommerce_checkout_create_order_line_item', 'replace_product_name_with_product_information', 10, 4);


function display_product_rating_and_review_count() {
  global $product;

  // Get average rating and review count
  $average_rating = $product->get_average_rating(); // Retrieves the average rating of the product.
  $review_count = $product->get_review_count(); // Retrieves the total number of reviews for the product.

  // Only display if the product has reviews
  if ($review_count > 0) {
      echo '<div class="product-rating-review">';
      
      // Display the star rating
      echo wc_get_rating_html($average_rating) . "rating across";     
      
      // Display the review count
      echo '<span class="review-count"> (' . $review_count . ' ' . _n('Review', 'Reviews', $review_count, 'woocommerce') . ')</span>';
      
      echo '</div>';
  }
}
add_action('woocommerce_single_product_summary', 'display_product_rating_and_review_count', 7);



