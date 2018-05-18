<?php

$installer = $this;

$installer->startSetup();

$installer->run("
    DROP TABLE IF EXISTS {$this->getTable('mgs_theme_layout_blocks')};
    CREATE TABLE {$this->getTable('mgs_theme_layout_blocks')} (
        `block_id` int(11) unsigned NOT NULL auto_increment,
        `page_type` enum('category', 'product', 'page', 'account', 'search', 'blog') NOT NULL,
        `page_id` int(11) NOT NULL default 0,
        `place` varchar(255) NOT NULL default '',
        `type` enum('static_block', 'category_navigation', 'sub_categories', 'layered_navigation', 'cart_sidebar', 'compare_sidebar', 'reorder_sidebar', 'poll', 'product_viewed', 'wishlist_sidebar', 'tags_popular', 'newsletter', 'promo_banner', 'menu', 'featured_products', 'bestseller_products', 'new_products', 'top_rate_products', 'sale_products', 'facebook_like_box', 'twitter_feed') NOT NULL,
        `title` varchar(255) NOT NULL default '',
        `options` TEXT NOT NULL default '',
        `sort_order` int(11) NOT NULL default 0,
      PRIMARY KEY (`block_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;    
");

$installer->endSetup();
