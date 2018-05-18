<?php

$installer = $this;

$installer->startSetup();

$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('mgs_catagory_setting')};
	CREATE TABLE {$this->getTable('mgs_theme_catagory_setting')} (
	  `category_id` int(11) unsigned NOT NULL,
	  `ratio` varchar(255) NOT NULL default '',
	  `desc_position` smallint(6) NOT NULL default '1',
	  `number_product_on_row` smallint(6) NOT NULL default '0',
	  PRIMARY KEY (`category_id`),
	  KEY `IDX_MGS_THEME_CATEGORY_ID` (`category_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();