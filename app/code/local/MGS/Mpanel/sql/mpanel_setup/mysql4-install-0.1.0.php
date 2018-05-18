<?php

$installer = $this;

$installer->startSetup();

$installer->run("

	-- DROP TABLE IF EXISTS {$this->getTable('mgs_theme_home')};
	CREATE TABLE {$this->getTable('mgs_theme_home')} (
	  `theme_id` int(11) unsigned NOT NULL auto_increment,
	  `name` varchar(255) NOT NULL default '',
	  `thumbnail` varchar(255) NOT NULL default '',
	  PRIMARY KEY (`theme_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	INSERT INTO `{$this->getTable('mgs_theme_home')}` (`name`, `thumbnail`) VALUES
	('1_column_1', '1_column_1.jpg'),('1_column_2', '1_column_2.jpg'),('2_columns_left_1', '2_columns_left_1.jpg'),('2_columns_left_2', '2_columns_left_2.jpg'),('2_columns_right_1', '2_columns_right_1.jpg'),('2_columns_right_2', '2_columns_right_2.jpg'),('3_columns_1', '3_columns_1.jpg'),('3_columns_2', '3_columns_2.jpg');

	-- DROP TABLE IF EXISTS {$this->getTable('mgs_theme_home_store')};
	CREATE TABLE {$this->getTable('mgs_theme_home_store')} (
	  `home_store_id` int(11) unsigned NOT NULL auto_increment,
	  `store_id` int(11) NULL,
	  `name` varchar(255) NOT NULL default '',
	  `status` smallint(6) NOT NULL default '0',
	  PRIMARY KEY (`home_store_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	-- DROP TABLE IF EXISTS {$this->getTable('mgs_theme_home_blocks')};
	CREATE TABLE {$this->getTable('mgs_theme_home_blocks')} (
	  `block_id` int(11) unsigned NOT NULL auto_increment,
	  `name` varchar(255) NOT NULL default '',
	  `theme_name` varchar(255) NOT NULL default '',
	  `class` varchar(255) NOT NULL default '',
	  `background` varchar(255) NULL,
	  `background_image` varchar(255) NOT NULL default '',
	  `background_repeat` smallint(6) NOT NULL default '0',
	  `parallax` smallint(6) NOT NULL default '0',
	  `fullwidth` smallint(6) NOT NULL default '0',
	  `padding_top` varchar(255) NOT NULL default '',
	  `padding_bottom` varchar(255) NOT NULL default '',
	  `store_id` int(11) NULL,
	  PRIMARY KEY (`block_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	-- DROP TABLE IF EXISTS {$this->getTable('mgs_theme_home_block_childs')};
	CREATE TABLE {$this->getTable('mgs_theme_home_block_childs')} (
	  `child_id` int(11) unsigned NOT NULL auto_increment,
	  `block_name` varchar(255) NOT NULL default '',
	  `home_name` varchar(255) NOT NULL default '',
	  `type` varchar(255) NOT NULL default '',
	  `position` int(11) NOT NULL default '0',
	  `setting` TEXT NOT NULL default '',
	  `col` smallint(6) NULL,
	  `class` varchar(255) NOT NULL default '',
	  `store_id` int(11) NULL,
	  `static_block_id` int(11) default '0',
	  PRIMARY KEY (`child_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	-- DROP TABLE IF EXISTS {$this->getTable('mgs_theme_layout')};
	CREATE TABLE {$this->getTable('mgs_theme_layout')} (
	  `layout_id` int(11) unsigned NOT NULL auto_increment,
	  `page_type` varchar(255) NOT NULL default '',
	  `layout` TEXT NOT NULL default '',
	  `page_layout` varchar(255) NOT NULL default '',
	  PRIMARY KEY (`layout_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    
");

$installer->endSetup(); 