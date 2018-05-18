<?php

$installer = $this;

$installer->startSetup();

$installer->run("

	-- DROP TABLE IF EXISTS {$this->getTable('mgs_megamenu')};
	CREATE TABLE {$this->getTable('mgs_megamenu')} (
	  `megamenu_id` int(11) unsigned NOT NULL auto_increment,
	  `title` varchar(255) NOT NULL default '',
	  `menu_type` smallint(6) NOT NULL default '1',
	  `url` varchar(255) NOT NULL default '',
	  `category_id` int(11) NOT NULL default '0',
	  `sub_category_ids` text NOT NULL default '',
	  `position` smallint(6) NOT NULL default '0',
	  `columns` smallint(6) NOT NULL default '0',
	  `align_menu` varchar(255) NOT NULL default '',
	  `align_dropdown` varchar(255) NOT NULL default '',
	  `max_level` smallint(6) NULL,
	  `dropdown_position` smallint(6) NOT NULL default '1',
	  `use_thumbnail` smallint(6) NOT NULL default '2',
	  `special_class` varchar(255) NOT NULL default '',
	  `static_content` text NOT NULL default '',
	  `top_content` text NOT NULL default '',
	  `bottom_content` text NOT NULL default '',
	  `left_content` text NOT NULL default '',
	  `right_content` text NOT NULL default '',
	  `status` smallint(6) NOT NULL default '0',
	  `from_date` datetime DEFAULT NULL,
	  `to_date` datetime DEFAULT NULL,
	  PRIMARY KEY (`megamenu_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	CREATE TABLE IF NOT EXISTS `{$this->getTable('mgs_megamenu_store')}` (
	  `megamenu_id` smallint(6) unsigned DEFAULT NULL,
	  `store_id` smallint(6) unsigned DEFAULT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup(); 