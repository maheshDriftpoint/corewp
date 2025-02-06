<?php 

function display_products_with_template($atts) {
    $atts = shortcode_atts([
        'posts_per_page' => 12,
    ], $atts, 'simple_product_display');
  
    // Get the current page number
    $paged = max(1, get_query_var('paged', 1));
  
    $args = [
        'post_type'      => 'product',
        'posts_per_page' => $atts['posts_per_page'],
        'paged'          => $paged,
    ]; 
  
    $query = new WP_Query($args);
    $totalProduct = $query->found_posts;
  
    if (!$query->have_posts()) {
        return '<p>No products found.</p>';
    }
  
    ob_start();

    echo '<div class="products-title"> <h3>Products Result '. $totalProduct .'</h3> </div>';

    while ($query->have_posts()) {
        $query->the_post();
        get_template_part('template-parts/product-content');
    }
  
    // Call the custom pagination function
    custom_pagination($paged, $query->max_num_pages);
  
    wp_reset_postdata();
  
    return ob_get_clean();
  }
  add_shortcode('simple_product_display', 'display_products_with_template');
  
  
  
  function simple_product_display_script() {
    ?>
    <script>
        jQuery(document).ready(function ($) {
        let selectedCategories = []; // Store selected categories globally
        let selectedPriceFilter = ''; // Store selected price filter globally

        // Handle category filter changes
        $('.category-filter').on('change', function () {
            selectedCategories = []; // Reset selected categories
            $('.category-filter:checked').each(function () {
                selectedCategories.push($(this).val());
            });
            fetchProducts(1); //Fetch products for the first page
        });

        // Handle price filter changes
        $('.price-filter').on('change', function () {
            selectedPriceFilter = $('input[name="price_filter"]:checked').val();
            console.log('Product Price Filter selected: ' + selectedPriceFilter);
            fetchProducts(1); // Fetch products for the first page
        });

        // Handle pagination links
        $(document).on('click', '.custom-pagination a', function (e) {
            e.preventDefault();
            let page = $(this).data('page'); 
            fetchProducts(page); // Fetch products for the selected page
        });        

        // Sync radio button with dropdown selection
        $('#price-filter-dropdown').on('change', function () {
            const selectedValue = $(this).val(); // Get the selected value from the dropdown
            $('.product-price-filter input[type="radio"]').prop('checked', false); // Uncheck all radio buttons
            if (selectedValue) {
                $('.product-price-filter input[type="radio"][value="' + selectedValue + '"]').prop('checked', true); 
                fetchProducts(1);
            }
        });

        // Fetch products via AJAX
        function fetchProducts(page) {
            $.ajax({
                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                type: "POST",
                data: {
                    action: "filter_products",
                    categories: selectedCategories,
                    price_filter: selectedPriceFilter,
                    paged: page,
                },
                beforeSend: function () {
                    $('#product-list').html('<p>Loading products...</p>');
                },
                success: function (response) {
                    $('#product-list').html(response);
                },
                error: function () {
                    $('#product-list').html('<p>Failed to load products. Please try again.</p>');
                },
            });
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'simple_product_display_script');

  
function filter_products_by_category() {
    // Get selected categories and page number from the request
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    $selected_categories = isset($_POST['categories']) ? array_map('intval', $_POST['categories']) : [];
    $price_filter = isset($_POST['price_filter']) ? sanitize_text_field($_POST['price_filter']) : '';

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => 12,
        'paged'          => $paged,
    ];

    if (!empty($selected_categories)) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $selected_categories,
            ],
        ];
    }

    // Add price sorting to the query
    if ($price_filter === 'low_to_high') {
        $args['orderby'] = 'meta_value_num';
        $args['meta_key'] = '_price'; //Assumes price is stored as a meta key
        $args['order'] = 'ASC';
    } elseif ($price_filter === 'high_to_low') {
        $args['orderby'] = 'meta_value_num';
        $args['meta_key'] = '_price';
        $args['order'] = 'DESC';
    }    

    $query = new WP_Query($args);   
    $totalProduct = $query->found_posts;

    if ($query->have_posts()) {
        ob_start();

        echo '<div class="products-title"> <h3>Products Result: '. $totalProduct .'</h3> </div>';
        // Output product content
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/product-content');
        }

        // Output pagination
        custom_pagination($paged, $query->max_num_pages);

        wp_reset_postdata();

        echo ob_get_clean();
    } else {
        echo '<p>No products found for the selected categories.</p>';
    }

    wp_die(); // Always end AJAX callbacks
}
add_action('wp_ajax_filter_products', 'filter_products_by_category');
add_action('wp_ajax_nopriv_filter_products', 'filter_products_by_category');

function custom_pagination($paged, $max_num_pages) {
    if ($max_num_pages <= 1) {
        return; // No pagination needed for a single page
    }

    $big = 999999999; // An unlikely integer for pagination replacement

    $pagination_links = paginate_links([
        'base'      => '%_%', // We will replace this later
        'format'    => '?paged=%#%',
        'current'   => $paged,
        'total'     => $max_num_pages,
        'prev_text' => __('&laquo; Previous', 'textdomain'),
        'next_text' => __('Next &raquo;', 'textdomain'),
        'type'      => 'array', // Output as an array for processing
    ]);


    if ($pagination_links) {
        echo '<nav class="custom-pagination"><ul class="pagination">';
        foreach ($pagination_links as $link) {
            // Modify each link to work with AJAX
            if (preg_match('/href=".*paged=(\d+)"/', $link, $matches)) {
                $page = intval($matches[1]);
                $link = preg_replace('/href=".*?"/', 'href="#" data-page="' . $page . '"', $link);
            } else if (strpos($link, 'current') !== false) {
                // If it's the current page, do not add data attributes
                $page = $paged;
            }

            echo '<li>' . $link . '</li>';
        }
        echo '</ul></nav>';
    }
}


