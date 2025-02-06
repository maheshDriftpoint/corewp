<?php
get_header(); // Include the header
?>

<div class="container my-4">
    <!-- Header Row -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h1 class="archive-title">Courses</h1>
        </div>
        <div class="col-md-6 text-md-end">
            <button class="btn btn-success">
                <a href="<?php echo site_url('/add-course/'); ?>" class="text-white text-decoration-none">Add Course</a>
            </button>
        </div>
    </div>

    <!-- Courses Section -->
    <?php if (have_posts()) : ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php while (have_posts()) : the_post(); ?>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="position-relative">
                            <a href="<?php the_permalink(); ?>">
                                <img src="<?php echo has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : site_url() . '/wp-content/uploads/2025/01/default.jpg'; ?>" 
                                    class="card-img-top rounded-top" alt="<?php the_title_attribute(); ?>">
                                <div class="overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                                    <span class="badge bg-primary text-white"><?php esc_html_e('View Course', 'textdomain'); ?></span>
                                </div>
                            </a>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark">
                                    <?php the_title(); ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted small mb-2">
                                <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                            </p>                       
                            <?php
                            $price = get_post_meta(get_the_ID(), '_course_price', true);
                             if ($price) : ?>
                                <p class="fw-bold text-success">$<?php echo esc_html($price); ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Replace Level with Course Type -->                       

                        <div class="card-footer d-flex justify-content-between">
                            <?php
                            // Display Course Types
                            $course_types = get_the_terms(get_the_ID(), 'course_type');
                            if (!empty($course_types) && !is_wp_error($course_types)) {
                                foreach ($course_types as $type) {
                                    echo '<span class="badge bg-secondary">' . esc_html($type->name) . '</span> ';
                                }
                            } else {
                                echo '<small class="text-muted">No course type assigned</small>';
                            }

                            // Display Duration
                            $duration = get_post_meta(get_the_ID(), '_course_duration', true);
                            if (!empty($duration)) {
                                echo '<small class="text-muted ms-auto">Duration: ' . esc_html($duration) . '</small>';
                            }
                            ?>
                        </div>


                        <!-- Action Buttons -->
                        <?php if (is_user_logged_in() && (current_user_can('edit_post', get_the_ID()) || current_user_can('delete_post', get_the_ID()))) : ?>
                            <div class="card-footer text-center">
                                <!-- Edit Button -->                               
                                <?php if (current_user_can('edit_post', get_the_ID())) : ?>
                                    <a href="<?php echo site_url('/edit-course/?course_id=' . get_the_ID()); ?>" class="btn btn-primary btn-sm">Edit</a>
                                <?php endif; ?>

                                <!-- Delete Button -->
                                <?php if (current_user_can('delete_post', get_the_ID())) : ?>
                                    <a href="<?php echo get_delete_post_link(get_the_ID()); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this course?');">Delete</a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <div class="row mt-4">
            <div class="col">
                <?php
                the_posts_pagination(array(
                    'mid_size'  => 2,
                    'prev_text' => __('&laquo; Previous', 'textdomain'),
                    'next_text' => __('Next &raquo;', 'textdomain'),
                    'class'     => 'pagination justify-content-center'
                ));
                ?>
            </div>
        </div>

    <?php else : ?>
        <div class="row">
            <div class="col">
                <p class="text-center"><?php _e('No courses found.', 'textdomain'); ?></p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
get_footer(); // Include the footer
?>
