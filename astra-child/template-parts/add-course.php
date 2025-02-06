<?php
/* Template Name: Add New Course */
get_header(); // Include the header
?>

<div class="container mb-4">
    <h1 class="add-course-title">Add New Course</h1>

    <?php
    // Check if the user is logged in
    if (is_user_logged_in()) :

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_course_nonce']) && wp_verify_nonce($_POST['add_course_nonce'], 'add_new_course')) {
            $title = sanitize_text_field($_POST['course_title']);
            $content = sanitize_textarea_field($_POST['course_content']);
            $duration = sanitize_text_field($_POST['course_duration']);
            $level = sanitize_text_field($_POST['course_level']);
            $price = sanitize_text_field($_POST['course_price']);
            $course_types = isset($_POST['course_type']) ? array_map('sanitize_text_field', $_POST['course_type']) : [];

            // Create a new course post
            $new_post = array(
                'post_title'   => $title,
                'post_content' => $content,
                'post_status'  => 'publish', //Set to 'publish' or 'draft' as needed
                'post_type'    => 'course',
                'meta_input'   => array(
                    '_course_duration' => $duration,
                    '_course_level'    => $level,
                    '_course_price'    => $price
                )
            );

            $post_id = wp_insert_post($new_post);

            if ($post_id) {
                // Assign selected course types
                if (!empty($course_types)) {
                    wp_set_post_terms($post_id, $course_types, 'course_type');
                }

                echo '<p class="success-message">Course added successfully! <a href="' . get_permalink($post_id) . '">View your course</a></p>';
            } else {
                echo '<p class="error-message">There was an error adding the course.</p>';
            }
        }

        // Fetch available course types
        $course_types = get_terms(array(
            'taxonomy'   => 'course_type',
            'hide_empty' => false,
        ));
    ?>

        <!-- Course Submission Form -->
        <form method="POST">
            <?php wp_nonce_field('add_new_course', 'add_course_nonce'); ?>

            <div class="form-group mb-4">
                <label for="course_title">Course Title</label>
                <input type="text" id="course_title" name="course_title" class="form-control" required>
            </div>

            <div class="form-group mb-4">
                <label for="course_content">Course Content</label>
                <textarea id="course_content" name="course_content" class="form-control" rows="5" required></textarea>
            </div>

            <div class="form-group mb-4">
                <label for="course_type">Course Type</label> 

                <select id="course_type" name="course_type[]" class="form-control"  required>
                    <?php 
                    // Check if we got any course types and display them
                    if (!empty($course_types) && !is_wp_error($course_types)) :
                        foreach ($course_types as $type) : ?>
                            <option value="<?php echo esc_attr($type->term_id); ?>">
                                <?php echo esc_html($type->name); ?>
                            </option>
                        <?php endforeach; 
                    else : ?>
                        <option value="">No course types found</option>
                    <?php endif; ?>
                </select>
            </div>


            <div class="form-group mb-4">
                <label for="course_duration">Duration</label>
                <input type="text" id="course_duration" name="course_duration" class="form-control" required>
            </div>

            <div class="form-group mb-4">
                <label for="course_level">Level</label>
                <input type="text" id="course_level" name="course_level" class="form-control" required>
            </div>

            <div class="form-group mb-4">
                <label for="course_price">Price</label>
                <input type="number" id="course_price" name="course_price" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Submit Course</button>
        </form>

    <?php else : ?>
        <p>You must be logged in to add a course. <a href="<?php echo wp_login_url(); ?>">Login here</a></p>
    <?php endif; ?>
</div>

<?php
get_footer(); // Include the footer
?>
