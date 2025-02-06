<div class="product">
    <?php if (has_post_thumbnail()) : ?>
        <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail('medium', ['class' => 'product-image']); ?>
        </a>
    <?php endif; ?>

    <h2 class="product-title">
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h2>

    <div class="product-price">
        <?php global $product; echo $product->get_price_html(); ?>
    </div>

    <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="add-to-cart">
        <?php esc_html_e('Add to Cart', 'woocommerce'); ?>
    </a>
</div>
