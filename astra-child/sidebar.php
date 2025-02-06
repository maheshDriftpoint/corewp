<?php

if (is_page('test')) { ?>
    
    <div class="product-price">
        <h4>Filter by Price</h4>
        <ul class="product-price-filter">
            <li><label><input type="radio" class="price-filter" name="price_filter" value="low_to_high"> Low to High</label></li>
            <li><label><input type="radio" class="price-filter" name="price_filter" value="high_to_low">High to Low</label></li>
        </ul>
    </div>

    <div class="product-categories">
        <h4>Product Categories</h4>
        <ul class="categories-list">
        <?php
            // Query to fetch product categories
            $categories = get_terms([
                'taxonomy' => 'product_cat',
                'hide_empty' => true,
                'number' => 10,
            ]);

            if (is_wp_error($categories) || empty($categories)) {
                echo '<p>No categories found.</p>';
            } else {
                //echo '<form id="category-form">';
                foreach ($categories as $category) : ?>
                    <div>
                        <label>
                            <input type="checkbox" class="category-filter" name="product_cat[]" value="<?php echo esc_attr($category->term_id); ?>">
                            <?php echo esc_html($category->name); ?>
                        </label>
                    </div>
                <?php endforeach;
                //echo '</form>';
            }
        ?>
        </ul>
    </div>

<?php
    
}

