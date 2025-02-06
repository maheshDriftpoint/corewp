<?php 
function mini_cart_script_and_css(){
    // Define version number
    $version = '1.13'; // Change this to your desired version number

    wp_enqueue_style('mini-cart', get_stylesheet_directory_uri()."/css/mini-cart.css", array(), $version);
    wp_enqueue_script('mini-cart', get_stylesheet_directory_uri()."/js/mini-cart.js", array(), $version, true);

    // Localize the script with AJAX URL
    wp_localize_script('mini-cart', 'MiniCartAjax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('mini_cart_nonce') // Secure the AJAX request
    ));
}
add_action('wp_enqueue_scripts','mini_cart_script_and_css');

function custom_mini_cart_shortcode() {
    ob_start(); ?>
    <div class="header-cart">
        <a href="#" id="cart-icon">
            <i class="fas fa-shopping-cart"></i>
            <span id="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
        </a>
        <div id="mini-cart-container" class="mini-cart">            
            <?php custom_woocommerce_mini_cart_html(); ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('custom_mini_cart', 'custom_mini_cart_shortcode');

function custom_woocommerce_mini_cart_html() {
    $cart_items = WC()->cart->get_cart();
    $cart_total = WC()->cart->get_cart_subtotal();
    $cart_count = WC()->cart->get_cart_contents_count();
    $free_shipping_threshold = 300; // Example free shipping threshold
    $cart_total_raw = WC()->cart->get_cart_contents_total();
    $remaining_for_free_shipping = $free_shipping_threshold - $cart_total_raw;

    ?>
    <div class="vns_sidecart">
        <div class="vns_sidecart_header">
            <h2>Cart (<?php echo $cart_count; ?> items)</h2>
            <a href="#" id="sidecart-close">
                <svg fill="#000000" width="24px" height="24px" viewBox="0 0 24 24">
                    <line x1="19" y1="19" x2="5" y2="5" style="stroke: rgb(0, 0, 0); stroke-width: 2;"></line>
                    <line x1="19" y1="5" x2="5" y2="19" style="stroke: rgb(0, 0, 0); stroke-width: 2;"></line>
                </svg>
            </a>
        </div>
        <div class="vns_sidecart_progressbar">
            <?php if ($remaining_for_free_shipping > 0): ?>
                <strong>Spend $<?php echo number_format($remaining_for_free_shipping, 2); ?> more for free shipping</strong>
            <?php else: ?>
                <strong>Congratulations! You have free shipping!</strong>
            <?php endif; ?>
            <div class="vns_sidecart_progressbar_color">
                <div class="vns_sidecart_progressbar_top_color" style="width: <?php echo min(100, ($cart_total_raw / $free_shipping_threshold) * 100); ?>%;"></div>
            </div>
        </div>
        <div class="vns_sidecart_content">
            <ul>
                <?php foreach ($cart_items as $cart_item_key => $cart_item):
                    $product = $cart_item['data'];
                    $product_name = $product->get_name();
                    $product_price = $product->get_price();
                    $product_image = wp_get_attachment_image_src($product->get_image_id(), 'thumbnail')[0];
                    $product_quantity = $cart_item['quantity'];
                ?>
                    <li>
                        <div class="vns_sidecart_content_img">
                            <img src="<?php echo $product_image; ?>" alt="<?php echo esc_attr($product_name); ?>">
                        </div>
                        <div class="vns_sidecart_content_des">
                            <a href="<?php echo get_permalink($product->get_id()); ?>"><?php echo $product_name; ?></a>
                            <div><span>Quantity:</span> <span><?php echo $product_quantity; ?></span></div>
                        </div>                       

                        <div class="vns_sidecart_content_icon">
                            <strong>$<?php echo number_format($product_price * $product_quantity, 2); ?></strong>
                        </div>

                        <!-- Quantity Controls -->
                        <div class="quantity-controls">
                            <button class="minus-btn update-cart" data-cart-key="<?php echo $cart_item_key; ?>" data-qty="<?php echo $product_quantity - 1; ?>">−</button>
                            <input type="number" class="cart-quantity" value="<?php echo $product_quantity; ?>" min="1" readonly>
                            <button class="plus-btn update-cart" data-cart-key="<?php echo $cart_item_key; ?>" data-qty="<?php echo $product_quantity + 1; ?>">+</button>
                        </div>  
                        
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="vns_sidecart_subtotal">
            <p class="vns_sidecart_total">
                <strong>Subtotal:</strong> <span><strong><?php echo $cart_total; ?></strong></span>
            </p>
            <p class="vns_sidecart_checkout_button">
                <a href="<?php echo wc_get_checkout_url(); ?>" class="button checkout">Checkout</a>
            </p>
        </div>
    </div>
    <?php
}

function update_mini_cart_ajax_handler() {
    check_ajax_referer('mini_cart_nonce', 'security');

    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    if ($product_id && $quantity) {
        WC()->cart->add_to_cart($product_id, $quantity);
    }

    ob_start();
    custom_woocommerce_mini_cart_html();
    $cart_html = ob_get_clean();

    wp_send_json_success([
        'mini_cart' => $cart_html,
        'cart_count' => WC()->cart->get_cart_contents_count(),
        'cart_total' => WC()->cart->get_cart_total(),
    ]);
}
add_action('wp_ajax_update_mini_cart', 'update_mini_cart_ajax_handler');
add_action('wp_ajax_nopriv_update_mini_cart', 'update_mini_cart_ajax_handler');


