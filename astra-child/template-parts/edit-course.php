<?php
/* Template Name: Edit Course */
get_header(); // Include the header

// Ensure the user is logged in
if (is_user_logged_in()) :

    // Check if the course ID is provided in the URL
    if (isset($_GET['course_id']) && is_numeric($_GET['course_id'])) :
        $course_id = intval($_GET['course_id']);

        // Fetch the course post to edit
        $course = get_post($course_id);

        // Check if the current user can edit this course
        if ($course && (current_user_can('edit_post', $course_id))) :

            // Handle form submission
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_course_nonce']) && wp_verify_nonce($_POST['edit_course_nonce'], 'edit_course')) {
                $title = sanitize_text_field($_POST['course_title']);
                $content = sanitize_textarea_field($_POST['course_content']);
                $duration = sanitize_text_field($_POST['course_duration']);
                $level = sanitize_text_field($_POST['course_level']);
                $price = sanitize_text_field($_POST['course_price']);
                $course_types = isset($_POST['course_type']) ? array_map('sanitize_text_field', $_POST['course_type']) : [];

                // Update the course post
                $updated_post = array(
                    'ID'            => $course_id, // Ensure it's updating the correct post
                    'post_title'    => $title,
                    'post_content'  => $content,
                    'meta_input'    => array(
                        '_course_duration' => $duration,
                        '_course_level'    => $level,
                        '_course_price'    => $price
                    ),
                );

                // Update the post in the database
                $post_id = wp_update_post($updated_post);

                if (!is_wp_error($post_id)) {
                    // Update course types (taxonomies)
                    if (!empty($course_types)) {
                        wp_set_post_terms($post_id, $course_types, 'course_type');
                    }

                    echo '<p class="success-message">Course updated successfully! <a href="' . get_permalink($post_id) . '">View your course</a></p>';
                } else {
                    echo '<p class="error-message">There was an error updating the course.</p>';
                }
            }

            // Fetch available course types
            $course_types = get_terms(array(
                'taxonomy'   => 'course_type',
                'hide_empty' => false,
            ));
        else :
            echo '<p>You do not have permission to edit this course.</p>';
        endif;
    else :
        echo '<p>No valid course selected for editing.</p>';
    endif;

else :
    echo '<p>You must be logged in to edit a course. <a href="' . wp_login_url() . '">Login here</a></p>';
endif;
?>

<div class="container mb-4">
    <h1 class="edit-course-title">Edit Course</h1>

    <!-- Course Edit Form -->
    <form method="POST">
        <?php wp_nonce_field('edit_course', 'edit_course_nonce'); ?>

        <div class="form-group mb-4">
            <label for="course_title">Course Title</label>
            <input type="text" id="course_title" name="course_title" class="form-control" value="<?php echo esc_attr($course->post_title); ?>" required>
        </div>

        <div class="form-group mb-4">
            <label for="course_content">Course Content</label>
            <textarea id="course_content" name="course_content" class="form-control" rows="5" required><?php echo esc_textarea($course->post_content); ?></textarea>
        </div>

        <div class="form-group mb-4">
            <label for="course_type">Course Type</label>
            <select id="course_type" name="course_type[]" class="form-control"  required>
                <?php
                if (!empty($course_types) && !is_wp_error($course_types)) :
                    $selected_course_types = wp_get_post_terms($course_id, 'course_type', array('fields' => 'ids'));
                    foreach ($course_types as $type) :
                ?>
                    <option value="<?php echo esc_attr($type->term_id); ?>" <?php echo in_array($type->term_id, $selected_course_types) ? 'selected' : ''; ?>>
                        <?php echo esc_html($type->name); ?>
                    </option>
                <?php endforeach; ?>
                <?php else : ?>
                    <option>No course types found</option>
                <?php endif; ?>
            </select>
        </div>

        <div class="form-group mb-4">
            <label for="course_duration">Duration</label>
            <input type="text" id="course_duration" name="course_duration" class="form-control" value="<?php echo esc_attr(get_post_meta($course_id, '_course_duration', true)); ?>" required>
        </div>

        <div class="form-group mb-4">
            <label for="course_level">Level</label>
            <input type="text" id="course_level" name="course_level" class="form-control" value="<?php echo esc_attr(get_post_meta($course_id, '_course_level', true)); ?>" required>
        </div>

        <div class="form-group mb-4">
            <label for="course_price">Price</label>
            <input type="number" id="course_price" name="course_price" class="form-control" value="<?php echo esc_attr(get_post_meta($course_id, '_course_price', true)); ?>" required>
        </div>

        <button type="submit" class="btn btn-success">Update Course</button>
    </form>

</div>

<?php
get_footer(); // Include the footer
?>
