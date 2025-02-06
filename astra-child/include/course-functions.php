<?php 

// Function to enqueue Bootstrap CSS and JS
function enqueue_bootstrap_assets() {
    // Bootstrap CSS
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', array(), '5.3.2');

    // Bootstrap JavaScript with Popper.js (required for tooltips, dropdowns, etc.)
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', array(), '5.3.2', true);
}

// Hook the function to 'wp_enqueue_scripts'
add_action('wp_enqueue_scripts', 'enqueue_bootstrap_assets');


function register_course_post_type() {
    $labels = array(
        'name'                  => _x('Courses', 'Post type general name', 'textdomain'),
        'singular_name'         => _x('Course', 'Post type singular name', 'textdomain'),
        'menu_name'             => _x('Courses', 'Admin Menu text', 'textdomain'),
        'name_admin_bar'        => _x('Course', 'Add New on Toolbar', 'textdomain'),
        'add_new'               => __('Add New', 'textdomain'),
        'add_new_item'          => __('Add New Course', 'textdomain'),
        'new_item'              => __('New Course', 'textdomain'),
        'edit_item'             => __('Edit Course', 'textdomain'),
        'view_item'             => __('View Course', 'textdomain'),
        'all_items'             => __('All Courses', 'textdomain'),
        'search_items'          => __('Search Courses', 'textdomain'),
        'parent_item_colon'     => __('Parent Courses:', 'textdomain'),
        'not_found'             => __('No courses found.', 'textdomain'),
        'not_found_in_trash'    => __('No courses found in Trash.', 'textdomain'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'course'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-welcome-learn-more', // Icon for the menu
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true, // Enable for Gutenberg
    );

    register_post_type('course', $args);
}
add_action('init', 'register_course_post_type');

function register_course_type_taxonomy() {
    $labels = array(
        'name'              => _x('Course Types', 'taxonomy general name', 'textdomain'),
        'singular_name'     => _x('Course Type', 'taxonomy singular name', 'textdomain'),
        'search_items'      => __('Search Course Types', 'textdomain'),
        'all_items'         => __('All Course Types', 'textdomain'),
        'parent_item'       => __('Parent Course Type', 'textdomain'),
        'parent_item_colon' => __('Parent Course Type:', 'textdomain'),
        'edit_item'         => __('Edit Course Type', 'textdomain'),
        'update_item'       => __('Update Course Type', 'textdomain'),
        'add_new_item'      => __('Add New Course Type', 'textdomain'),
        'new_item_name'     => __('New Course Type Name', 'textdomain'),
        'menu_name'         => __('Course Types', 'textdomain'),
    );

    $args = array(
        'hierarchical'      => true, // true for category-like, false for tag-like
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'course-type'),
        'show_in_rest'      => true, // Enable for Gutenberg
    );

    register_taxonomy('course_type', array('course'), $args);
}
add_action('init', 'register_course_type_taxonomy');


function add_course_meta_box() {
    add_meta_box(
        'course_info_meta_box', // Meta box ID
        'Course Information',   // Title
        'display_course_meta_box', // Callback function
        'course',               // Post type
        'normal',               // Context (normal, side, advanced)
        'high'                  // Priority
    );
}
add_action('add_meta_boxes', 'add_course_meta_box');

function display_course_meta_box($post) {
    // Retrieve current values from the database
    $duration = get_post_meta($post->ID, '_course_duration', true);
    $level = get_post_meta($post->ID, '_course_level', true);
    $price = get_post_meta($post->ID, '_course_price', true);

    // Security nonce
    wp_nonce_field('save_course_meta_box', 'course_meta_box_nonce');

    ?>
    <table class="form-table">
        <tr>
            <th><label for="course_duration">Duration :</label></th>
            <td><input type="text" name="course_duration" id="course_duration" value="<?php echo esc_attr($duration); ?>" size="30"></td>
        </tr>
        <tr>
            <th><label for="course_level">Level :</label></th>
            <td><input type="text" name="course_level" id="course_level" value="<?php echo esc_attr($level); ?>" size="30"></td>
        </tr>
        <tr>
            <th><label for="course_price">Price :</label></th>
            <td><input type="text" name="course_price" id="course_price" value="<?php echo esc_attr($price); ?>" size="30"></td>
        </tr>
    </table>
    <?php
}


function save_course_meta_box($post_id) {
    // Check nonce for security
    if (!isset($_POST['course_meta_box_nonce']) || !wp_verify_nonce($_POST['course_meta_box_nonce'], 'save_course_meta_box')) {
        return;
    }

    // Check if this is an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save Duration
    if (isset($_POST['course_duration'])) {
        update_post_meta($post_id, '_course_duration', sanitize_text_field($_POST['course_duration']));
    }

    // Save Level
    if (isset($_POST['course_level'])) {
        update_post_meta($post_id, '_course_level', sanitize_text_field($_POST['course_level']));
    }

    // Save Price
    if (isset($_POST['course_price'])) {
        update_post_meta($post_id, '_course_price', sanitize_text_field($_POST['course_price']));
    }
}
add_action('save_post', 'save_course_meta_box');

function register_lecture_post_type() {
    $labels = array(
        'name'               => _x('Lectures', 'post type general name', 'textdomain'),
        'singular_name'      => _x('Lecture', 'post type singular name', 'textdomain'),
        'menu_name'          => _x('Lectures', 'admin menu', 'textdomain'),
        'name_admin_bar'     => _x('Lecture', 'add new on admin bar', 'textdomain'),
        'add_new'            => __('Add New', 'textdomain'),
        'add_new_item'       => __('Add New Lecture', 'textdomain'),
        'new_item'           => __('New Lecture', 'textdomain'),
        'edit_item'          => __('Edit Lecture', 'textdomain'),
        'view_item'          => __('View Lecture', 'textdomain'),
        'all_items'          => __('All Lectures', 'textdomain'),
        'search_items'       => __('Search Lectures', 'textdomain'),
        'parent_item_colon'  => __('Parent Lectures:', 'textdomain'),
        'not_found'          => __('No lectures found.', 'textdomain'),
        'not_found_in_trash' => __('No lectures found in Trash.', 'textdomain')
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true, // Visible on the front-end
        'publicly_queryable' => true, // Can be queried via URL
        'show_ui'            => true, // Show in the WordPress admin
        'show_in_menu'       => true, // Show in the admin menu
        'query_var'          => true,
        'rewrite'            => array('slug' => 'lectures'), // URL slug
        'capability_type'    => 'post',
        'has_archive'        => true, // Enable archive
        'hierarchical'       => false, // Non-hierarchical like posts
        'menu_position'      => 5, // Position in the admin menu
        'menu_icon'          => 'dashicons-welcome-learn-more', // Dashicon for the menu
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'), // Supported features
        'show_in_rest'       => true // Enable Gutenberg editor
    );

    register_post_type('lecture', $args);
}
add_action('init', 'register_lecture_post_type');

function register_course_lectures_taxonomy() {
    $labels = array(
        'name'              => _x('Courses', 'taxonomy general name', 'textdomain'),
        'singular_name'     => _x('Course', 'taxonomy singular name', 'textdomain'),
        'search_items'      => __('Search Courses', 'textdomain'),
        'all_items'         => __('All Courses', 'textdomain'),
        'edit_item'         => __('Edit Course', 'textdomain'),
        'update_item'       => __('Update Course', 'textdomain'),
        'add_new_item'      => __('Add New Course', 'textdomain'),
        'new_item_name'     => __('New Course Name', 'textdomain'),
        'menu_name'         => __('Courses', 'textdomain'),
    );

    $args = array(
        'hierarchical'      => true, // Set to true if you want a hierarchical structure
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'course'),
        'show_in_rest'      => true, // Enable for Gutenberg
    );

    register_taxonomy('course_lectures', array('lecture'), $args);
}
add_action('init', 'register_course_lectures_taxonomy');

// Add the meta box to the 'lecture' post type
add_action('add_meta_boxes', 'lecture_add_course_meta_box');
function lecture_add_course_meta_box() {
    add_meta_box(
        'course_meta_box',               // Unique ID for the meta box
        'Select Course',                 // Title of the meta box
        'render_course_meta_box',        // Callback function to render the meta box content
        'lecture',                       // The post type to which the meta box will be added
        'normal',                          // Position on the screen ('normal', 'side', 'advanced')
        'default'                        // Priority (default)
    );
}

// Render the course selection dropdown
function render_course_meta_box($post) {
    // Get the current selected course ID (if any)
    $selected_course_id = get_post_meta($post->ID, '_selected_course', true);
    
    // Fetch all the courses (assuming the post type for courses is 'course')
    $courses = get_posts(array(
        'post_type'   => 'course',   // The course post type
        'post_status' => 'publish',  // Only published courses
        'numberposts' => -1,         // Get all courses
        'orderby'     => 'title',    // Order by title
        'order'       => 'ASC'       // Ascending order
    ));

    // Add a nonce for security
    wp_nonce_field('save_course_meta_box', 'course_meta_box_nonce');

    // Render the dropdown
    echo '<label for="selected_course">Choose a Course:</label><br>';
    echo '<select name="selected_course" id="selected_course" style="width:100%;">';
    echo '<option value="">Select a course</option>'; // Default empty option

    // Loop through the courses and create an option for each
    foreach ($courses as $course) {
        echo '<option value="' . esc_attr($course->ID) . '" ' . selected($selected_course_id, $course->ID, false) . '>' . esc_html($course->post_title) . '</option>';
    }

    echo '</select>';
}

// Save the selected course ID when the Lecture post is saved
add_action('save_post', 'lecture_save_course_meta_box');
function lecture_save_course_meta_box($post_id) {    

    // Check if the user has permission to save the post
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Check if the course is set and save it
    if (isset($_POST['selected_course'])) {
        $course_id = sanitize_text_field($_POST['selected_course']);
        update_post_meta($post_id, '_selected_course', $course_id);
    } else {
        // If no course is selected, delete the existing meta value
        delete_post_meta($post_id, '_selected_course');
    }
}

// Optionally, display the selected course in the admin list (optional)
/* add_filter('manage_lecture_posts_columns', 'add_course_column');
function add_course_column($columns) {
    $columns['course'] = 'Course';
    return $columns;
}

add_action('manage_lecture_posts_custom_column', 'render_course_column', 10, 2);
function render_course_column($column, $post_id) {
    if ($column === 'course') {
        $course_id = get_post_meta($post_id, '_selected_course', true);
        echo $course_id ? get_the_title($course_id) : 'None';
    }
} */





