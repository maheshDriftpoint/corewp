
<?php 
// Add custom meta box for footer selection
function add_footer_selection_meta_box() {
  add_meta_box(
      'footer_selection_meta_box', // ID
      'Footer Selection', // Title
      'footer_selection_meta_box_callback', // Callback function
      'page', // Screen (in this case, pages)
      'side', // Context (where it will appear)
      'high' // Priority
  );
}
add_action('add_meta_boxes', 'add_footer_selection_meta_box');

// Callback function to display the custom field
function footer_selection_meta_box_callback($post) {
  // Retrieve the current value
  $footer_selection = get_post_meta($post->ID, '_footer_selection', true);
  
  // Add nonce for security
  wp_nonce_field('save_footer_selection_meta_box', 'footer_selection_meta_box_nonce');
  
  // Dropdown for selecting footer option
  ?>
  <label for="footer_selection">Choose Footer:</label>
  <select name="footer_selection" id="footer_selection" class="postbox">
      <option value="elementor" <?php selected($footer_selection, 'elementor'); ?>>Elementor Footer</option>
      <option value="custom" <?php selected($footer_selection, 'custom'); ?>>Theme Footer</option>
  </select>
  <?php
}

// Save the custom field value
function save_footer_selection_meta_box($post_id) {
  // Check if nonce is valid
  if (!isset($_POST['footer_selection_meta_box_nonce']) || !wp_verify_nonce($_POST['footer_selection_meta_box_nonce'], 'save_footer_selection_meta_box')) {
      return;
  }
  
  // Check if the footer selection field is set
  if (isset($_POST['footer_selection'])) {
      // Save the selected footer option
      update_post_meta($post_id, '_footer_selection', sanitize_text_field($_POST['footer_selection']));
  }
}
add_action('save_post', 'save_footer_selection_meta_box');


function custom_footer_content() {
  // Get the current page ID
  $page_id = get_queried_object_id();
  
  // Retrieve the selected footer type from the custom field
  $footer_selection = get_post_meta($page_id, '_footer_selection', true);

  if ($footer_selection == 'elementor') {
      // Display Elementor Footer (use the Elementor template ID, e.g., 4753)
      if (class_exists('Elementor\Plugin')) {
          echo \Elementor\Plugin::instance()->frontend->get_builder_content(4753);
      } else {
          echo 'Elementor Footer not available.';
      }
  } else {
      // Display Custom Footer
      // Add your custom footer HTML here or use a footer template
      ?>
      <div class="custom-footer">          
          <?php 
              astra_content_after();
    
              astra_footer_before();
                  
              astra_footer();
                  
              astra_footer_after(); 
          ?>
      </div>
      <?php
  }
}

// Hook the custom footer content into WordPress footer action
add_action('wp_footer', 'custom_footer_content');


function register_footer_menu() {
    register_nav_menu('footer-first-menu', __('Footer First Menu'));
}
add_action('init', 'register_footer_menu');

function custom_footer_menu_shortcode() {
    ob_start(); // Start output buffering

    ?>
    <div class="footer-menu">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'footer-first-menu',
            'container'      => 'nav',
            'menu_class'     => 'footer-menu-list',
        ));
        ?>
    </div>
    <?php

    return ob_get_clean(); // Return the buffered content
}
add_shortcode('footer_first_menu', 'custom_footer_menu_shortcode');


//create checkbox gutenberg editor disable 
function custom_gutenberg_settings() {
    add_settings_section(
        'disable_gutenberg_section',
        'Disable Gutenberg Editor',
        'disable_gutenberg_section_callback',
        'general'
    );

    add_settings_field(
        'disable_gutenberg_posts',
        'Disable Gutenberg for Posts',
        'disable_gutenberg_posts_callback',
        'general',
        'disable_gutenberg_section'
    );
    register_setting('general', 'disable_gutenberg_posts');

    add_settings_field(
        'disable_gutenberg_pages',
        'Disable Gutenberg for Pages',
        'disable_gutenberg_pages_callback',
        'general',
        'disable_gutenberg_section'
    );
    register_setting('general', 'disable_gutenberg_pages');

    add_settings_field(
        'disable_gutenberg_widgets',
        'Disable Gutenberg for Widgets',
        'disable_gutenberg_widgets_callback',
        'general',
        'disable_gutenberg_section'
    );
    register_setting('general', 'disable_gutenberg_widgets');
}
add_action('admin_init', 'custom_gutenberg_settings');

function disable_gutenberg_section_callback() {
    echo '<p>Select where you want to disable the Gutenberg editor.</p>';
}

function disable_gutenberg_posts_callback() {
    $option = get_option('disable_gutenberg_posts');
    echo '<input type="checkbox" name="disable_gutenberg_posts" value="1" ' . checked(1, $option, false) . ' />';
}

function disable_gutenberg_pages_callback() {
    $option = get_option('disable_gutenberg_pages');
    echo '<input type="checkbox" name="disable_gutenberg_pages" value="1" ' . checked(1, $option, false) . ' />';
}

function disable_gutenberg_widgets_callback() {
    $option = get_option('disable_gutenberg_widgets');
    echo '<input type="checkbox" name="disable_gutenberg_widgets" value="1" ' . checked(1, $option, false) . ' />';
}


function disable_gutenberg_editor($can_edit, $post_type) {
    if (
        ($post_type == 'post' && get_option('disable_gutenberg_posts')) ||
        ($post_type == 'page' && get_option('disable_gutenberg_pages'))
    ) {
        return false; // Disable Gutenberg
    }
    return $can_edit;
}
add_filter('use_block_editor_for_post_type', 'disable_gutenberg_editor', 10, 2);

function disable_gutenberg_widgets() {
    if (get_option('disable_gutenberg_widgets')) {
        remove_theme_support('widgets-block-editor');
    }
}
add_action('after_setup_theme', 'disable_gutenberg_widgets');



//Create dynamic sidebar 
function custom_sidebar_menu() {
    add_menu_page(
        'Custom Sidebars', // Page Title
        'Custom Sidebars', // Menu Title
        'manage_options',  // Capability
        'custom-sidebars', // Menu Slug
        'custom_sidebar_page', // Function to display the page
        'dashicons-welcome-widgets-menus', // Icon
        20  // Position
    );
}
add_action('admin_menu', 'custom_sidebar_menu');

function custom_sidebar_page() {    
    ?>
    <div class="wrap">
        <h1>Manage Custom Sidebars</h1>
        <p style="color: #555;">Use this form to create or remove custom sidebars.</p>

        <!-- Sidebar Creation Form -->
        <form method="post" action="" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); max-width: 500px;">
            <table class="form-table">
                <tr>
                    <th><label for="sidebar_title">Sidebar Title:</label></th>
                    <td>
                        <input type="text" id="sidebar_title" name="sidebar_title" class="regular-text" required placeholder="Enter Sidebar Title">
                    </td>
                </tr>
                <tr>
                    <th><label for="subsite">Subsite (Optional):</label></th>
                    <td>
                        <input type="text" id="subsite" name="subsite" class="regular-text" placeholder="Enter Subsite Name">
                    </td>
                </tr>
            </table>

            <p>
                <button type="submit" name="create_sidebar" class="button button-primary">Create Sidebar</button>
            </p>
        </form>

        <?php
        // Handle Sidebar Creation
        if (isset($_POST['create_sidebar'])) {
            $sidebar_title = sanitize_text_field($_POST['sidebar_title']);
            $subsite = sanitize_text_field($_POST['subsite']);

            if (!empty($sidebar_title)) {
                $sidebars = get_option('custom_sidebars', []);
                $sidebars[] = ['title' => $sidebar_title, 'subsite' => $subsite];
                update_option('custom_sidebars', $sidebars);

                echo "<div style='margin-top: 10px; padding: 10px; background: #d4edda; border-left: 5px solid #28a745; color: #155724;'>
                    <strong>Success:</strong> Sidebar <strong>$sidebar_title</strong> created successfully!
                </div>";
            }
        }

        // Handle Sidebar Deletion
        if (isset($_POST['delete_sidebar'])) {
            $sidebar_to_delete = sanitize_text_field($_POST['delete_sidebar']);
            $sidebars = get_option('custom_sidebars', []);

            foreach ($sidebars as $key => $sidebar) {
                if ($sidebar['title'] === $sidebar_to_delete) {
                    unset($sidebars[$key]); // Remove from array
                    update_option('custom_sidebars', array_values($sidebars)); // Save updated list
                    echo "<div style='margin-top: 10px; padding: 10px; background: #f8d7da; border-left: 5px solid #dc3545; color: #721c24;'>
                        <strong>Deleted:</strong> Sidebar <strong>$sidebar_to_delete</strong> has been removed.
                    </div>";
                    break;
                }
            }
        }

        // Display Existing Sidebars
        $sidebars = get_option('custom_sidebars', []);
        if (!empty($sidebars)) {
            echo "<h2>Existing Sidebars</h2>";
            echo "<table class='widefat fixed' style='max-width: 500px;'>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Subsite</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>";

            foreach ($sidebars as $sidebar) {
                echo "<tr>
                    <td>{$sidebar['title']}</td>
                    <td>{$sidebar['subsite']}</td>
                    <td>
                        <form method='post' style='display:inline;'>
                            <input type='hidden' name='delete_sidebar' value='{$sidebar['title']}'>
                            <button type='submit' class='button button-danger' style='background: #dc3545; color: #fff; border: none; padding: 5px 10px; cursor: pointer;'>Delete</button>
                        </form>
                    </td>
                </tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "<p style='color: #777;'>No sidebars found.</p>";
        }
        ?>
    </div>

    <div class="wrap">
        <h1>Dynamic Menu Settings</h1>
        <form method="post" action="">
            <?php wp_nonce_field('custom_menu_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th><label for="menu_title">Enter Menu Title:</label></th>
                    <td><input type="text" id="menu_title" name="menu_title" value="<?php echo esc_attr($menu_title); ?>" class="regular-text" required></td>
                </tr>
            </table>
            <p><button type="submit" name="save_menu_title" class="button button-primary">Save Menu Title</button></p>
        </form>
    </div>

    <?php
}


function register_dynamic_menus() {
    $menu_title = get_option('custom_menu_title', 'Main Menu');

    register_nav_menu('dynamic_menu', esc_html($menu_title));
}
add_action('after_setup_theme', 'register_dynamic_menus');


function register_dynamic_sidebars() {
    $sidebars = get_option('custom_sidebars', []);

    if (!empty($sidebars)) {
        foreach ($sidebars as $sidebar) {
            register_sidebar([
                'name'          => $sidebar['title'],
                'id'            => sanitize_title($sidebar['title']),
                'before_widget' => '<div class="widget">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3>',
                'after_title'   => '</h3>',
            ]);
        }
    }
}
add_action('widgets_init', 'register_dynamic_sidebars');

