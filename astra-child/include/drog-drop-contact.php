<?php 

function enqueue_drag_drop_form_builder() {
    $random_version = rand(9, 99);    
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-draggable');
    wp_enqueue_script('jquery-ui-droppable');
    wp_enqueue_script('form-builder', get_stylesheet_directory_uri() . '/assets/js/form-builder.js', array('jquery', 'jquery-ui-draggable', 'jquery-ui-droppable'), $random_version, true);
    //wp_enqueue_script('form-builder', get_stylesheet_directory_uri() . '/assets/js/form-builder.js');
    wp_enqueue_style('form-builder-style', get_stylesheet_directory_uri() . '/assets/css/form-builder.css',array(), $random_version);
    //wp_enqueue_style('form-builder-style', get_stylesheet_directory_uri() . '/assets/css/form-builder.css', array(), rand());

}
add_action('wp_enqueue_scripts', 'enqueue_drag_drop_form_builder');




// AJAX handler to save form configuration
function save_form_configuration() {
    echo '<pre>';
    print_r($_POST);

    die; 
    
    if (!current_user_can('edit_posts')) {
        wp_send_json_error('Unauthorized', 403);
    }

    if (isset($_POST['fields']) && is_array($_POST['fields'])) {
        $form_id = sanitize_text_field($_POST['form_id']);
        $fields = array_map('sanitize_text_field', $_POST['fields']);
        update_option('drag_and_drop_form_' . $form_id, $fields); // Save fields in the database
        wp_send_json_success('Form saved successfully!');
    }

    wp_send_json_error('Invalid data', 400);
}
add_action('wp_ajax_save_form_configuration', 'save_form_configuration');


// Shortcode to display the form
function render_drag_and_drop_form($atts) {
    $atts = shortcode_atts(array(
        'id' => 'default', // Form ID
    ), $atts, 'drag_and_drop_form');

    $fields = get_option('drag_and_drop_form_' . $atts['id'], array());
    if (empty($fields)) {
        return '<p>No fields found for this form.</p>';
    }

    $html = '<form class="drag-and-drop-form">';
    foreach ($fields as $field) {
        switch ($field) {
            case 'first-name':
                $html .= '<div><label>Name:</label><input type="text" name="first_name" /></div>';
                break;
            case 'email':
                $html .= '<div><label>Email:</label><input type="email" name="email" /></div>';
                break;
            case 'phone':
                $html .= '<div><label>Phone:</label><input type="tel" name="phone" /></div>';
                break;
            case 'address':
                $html .= '<div><label>Address:</label><textarea name="address"></textarea></div>';
                break;
            case 'submit':
                $html .= '<div><input type="submit" class="btn btn-success form-submit" value="Submit" /></div>';
                break;
        }
        
    }
    $html .= '</form>';

    return $html;
}
add_shortcode('drag_and_drop_form', 'render_drag_and_drop_form');
