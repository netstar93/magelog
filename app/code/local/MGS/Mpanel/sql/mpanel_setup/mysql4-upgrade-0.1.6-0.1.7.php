<?php

$installer = $this;

$installer->startSetup();

$installer->run("
	CREATE TABLE {$this->getTable('mgs_catagory_setting')} (
	  `category_id` varchar(255) NOT NULL default '',
	  `ratio` varchar(255) NOT NULL default '',
	  `desc_position` smallint(6) NOT NULL default '1',
	  `number_product_on_row` smallint(6) NOT NULL default '0',
	  PRIMARY KEY (`category_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup(); 