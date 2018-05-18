<?php

$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE {$this->getTable('mgs_theme_layout_blocks')} MODIFY `type` enum('static_block', 'category_navigation', 'sub_categories', 'layered_navigation', 'cart_sidebar', 'compare_sidebar', 'reorder_sidebar', 'poll', 'product_viewed', 'product_related', 'wishlist_sidebar', 'tags_popular', 'newsletter', 'promo_banner', 'menu', 'featured_products', 'bestseller_products', 'new_products', 'top_rate_products', 'sale_products', 'facebook_like_box', 'twitter_feed') NOT NULL;    
");

$installer->endSetup();
