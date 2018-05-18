<?php

$installer = $this;

$installer->startSetup();

$installer->run("
	-- DROP TABLE IF EXISTS {$this->getTable('mgs_deals')};
	CREATE TABLE {$this->getTable('mgs_deals')} (
	  `deals_id` int(11) unsigned NOT NULL auto_increment,
	  `product_id` int(11) NULL,
	  `product_name` varchar(255) NOT NULL default '',
	  `price` varchar(255) NOT NULL default '',
	  `special_price` varchar(255) NOT NULL default '',
	  `qty` varchar(255) NOT NULL default '',
	  `deal_qty` varchar(255) NOT NULL default '',
	  `max_deal_qty` varchar(255) NOT NULL default '',
	  `sold` varchar(255) NOT NULL default '0',
	  `status` smallint(6) NOT NULL default '1',
	  `start_time` datetime NULL,
	  `end_time` datetime NULL,
	  PRIMARY KEY (`deals_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$setup->addAttribute('catalog_product', 'aht_deal_qty', array(
    'group'         				=> 'Prices',
    'input'         				=> 'text',
    'type'          				=> 'text',
    'label'         				=> 'Deal Quantity',
	'is_configurable'				=> 1,
	'apply_to'                		=> 'simple',
    'backend'       				=> '',
	'frontend'						=> '',
    'visible'       				=> false,
    'required'      				=> false,
    'user_defined' 					=> false,
    'searchable' 					=> false,
    'filterable' 					=> false,
    'comparable'    				=> false,
    'visible_on_front' 				=> false,
    'visible_in_advanced_search'  	=> false,
    'is_html_allowed_on_front' 		=> false,
    'global'        				=> Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE
));

	
$installer->endSetup(); 