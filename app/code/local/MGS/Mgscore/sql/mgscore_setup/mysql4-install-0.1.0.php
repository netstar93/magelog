<?php

$installer = $this;

$installer->startSetup();

$installer->run("
INSERT INTO {$this->getTable('permission_block')} (`block_name` ,`is_allowed`) VALUES 
('mpanel/navigation', 1), 
('mpanel/products', 1), 
('mpanel/product_special', 1), 
('testimonial/testimonial', 1), 
('revslider/slider_preview', 1), 
('mpanel/product_new', 1), 
('mpanel/product_sale', 1), 
('mpanel/product_rate', 1), 
('brand/homeblock', 1), 
('promobanners/promobanners', 1), 
('deals/widget', 1), 
('blog/last', 1), 
('blog/tab', 1), 
('event/widget', 1), 
('profiles/widget', 1), 
('megamenu/horizontal', 1), 
('megamenu/vertical', 1), 
('megamenu/megamenu', 1), 
('social/fblikebox', 1), 
('lookbook/widget_lookbook', 1),
('lookbook/widget_slider', 1),
('portfolio/portfolio', 1),
('tag/popular', 1); 
");

$installer->endSetup(); 