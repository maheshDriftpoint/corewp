<?php
get_header(); // Include the header
?>

<div class="container py-5">
    <div class="row">
        <!-- Sidebar with Lecture List -->
        <div class="col-md-4">
            <aside class="lecture-sidebar border rounded p-4 shadow-sm bg-light">
                <h2 class="h5 text-primary mb-4">All Lectures</h2>
                <?php 
                $args = array(
                    'post_type'      => 'lecture',
                    'post_status'    => 'publish',
                    'posts_per_page' => -1,
                    'orderby'        => 'title',
                    'order'          => 'ASC',
                );
                
                $query = new WP_Query($args);

                if ($query->have_posts()) {
                    echo '<ul class="list-group">';
                    while ($query->have_posts()) {
                        $query->the_post();
                        echo '<li class="list-group-item">';
                        echo '<a href="' . get_permalink() . '" class="text-decoration-none text-dark">' . get_the_title() . '</a>';
                        echo '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo '<p class="text-muted">No lectures found.</p>';
                }
                
                wp_reset_postdata();
                ?>
            </aside>
        </div>

        <!-- Main Content Area -->
        <div class="col-md-8">
            <main class="lecture-main-content">
                <?php
                if (have_posts()) :
                    while (have_posts()) : the_post();
                ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class('single-lecture border rounded p-4 shadow-sm bg-white'); ?>>
                            <header class="lecture-header mb-4">
                                <h1 class="lecture-title h3 text-primary"><?php the_title(); ?></h1>
                                <p class="text-muted">Published on: <?php echo get_the_date(); ?></p>
                            </header>

                            <div class="lecture-meta mb-3">
                                <?php
                                // Display custom fields using Advanced Custom Fields (if applicable)
                                if (function_exists('get_field')) :
                                    $lecture_duration = get_field('duration');
                                    $lecture_instructor = get_field('instructor');

                                    if ($lecture_duration) :
                                        echo '<p><strong>Duration:</strong> ' . esc_html($lecture_duration) . '</p>';
                                    endif;

                                    if ($lecture_instructor) :
                                        echo '<p><strong>Instructor:</strong> ' . esc_html($lecture_instructor) . '</p>';
                                    endif;
                                endif;
                                ?>
                            </div>

                            <div class="lecture-content">
                                <?php the_content(); ?>
                            </div>
                        </article>
                <?php
                    endwhile;
                else :
                    echo '<p class="text-muted">No lecture found.</p>';
                endif;
                ?>
            </main>
        </div>
    </div>
</div>

<?php
get_footer();
?>
