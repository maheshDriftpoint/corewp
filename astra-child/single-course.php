<?php
get_header(); // Include the header
?>


<div class="container py-5">
    <div class="row">
        <!-- Course Details -->
        <div class="col-md-4 mb-4">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('single-course border rounded p-4 shadow-sm'); ?>>
                    <header class="course-header mb-3">
                        <h1 class="course-title h4 text-primary"><?php the_title(); ?></h1>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="course-thumbnail mb-3">
                                <?php the_post_thumbnail('large', ['class' => 'img-fluid rounded']); ?>
                            </div>
                        <?php endif; ?>
                    </header>

                    <div class="course-meta mb-3">
                        <?php
                        // Retrieve and display custom metadata
                        $duration = get_post_meta(get_the_ID(), '_course_duration', true);
                        $level = get_post_meta(get_the_ID(), '_course_level', true);
                        $price = get_post_meta(get_the_ID(), '_course_price', true);

                        if ($duration) {
                            echo '<p><strong>Duration:</strong> ' . esc_html($duration) . '</p>';
                        }
                        if ($level) {
                            echo '<p><strong>Level:</strong> ' . esc_html($level) . '</p>';
                        }
                        if ($price) {
                            echo '<p><strong>Price:</strong> $' . esc_html($price) . '</p>';
                        }
                        ?>
                    </div>

                    <div class="course-content">
                        <?php the_content(); ?>
                    </div>

                    <footer class="course-footer mt-3">
                        <div class="course-categories">
                            <?php
                            // Display Course Type taxonomy terms
                            $terms = get_the_terms(get_the_ID(), 'course_type');
                            if ($terms && !is_wp_error($terms)) {
                                echo '<p><strong>Course Types:</strong> ';
                                $term_links = array_map(function ($term) {
                                    return '<a href="' . esc_url(get_term_link($term)) . '" class="text-secondary">' . esc_html($term->name) . '</a>';
                                }, $terms);
                                echo implode(', ', $term_links);
                                echo '</p>';
                            }
                            ?>
                        </div>
                    </footer>
                </article>        
            <?php endwhile; endif; ?>
        </div>

        <!-- Lectures Section -->
        <div class="col-md-8">
            <div class="lectures-section bg-light p-4 rounded shadow-sm">
                <h2 class="h5 text-primary mb-4">Lectures for this Course</h2>
                <?php 
                $course_id = get_the_ID();
                $args = array(
                    'post_type'   => 'lecture', 
                    'post_status' => 'publish',
                    'meta_query'  => array(
                        array(
                            'key'     => '_selected_course', 
                            'value'   => $course_id,        
                            'compare' => '=',               
                        ),
                    ),
                    'orderby'     => 'title', 
                    'order'       => 'ASC',
                );
                
                $query = new WP_Query($args);
                
                if ($query->have_posts()) {
                    echo '<ul class="list-group">';
                    while ($query->have_posts()) {
                        $query->the_post();
                        $lecture_title = get_the_title();                 
                        echo '<li class="list-group-item">';
                        echo '<a href="' . get_permalink() . '" class="text-decoration-none text-dark">' . $lecture_title . '</a>'; 
                        echo '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo '<p class="text-muted">No lectures found for the selected course.</p>';
                }
                
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
?>
