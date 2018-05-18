<?php
$installer = $this;
$installer->startSetup();

$installer->run("

	CREATE TABLE {$this->getTable('mgs_lookbook_slide')} (
	  `slide_id` int(11) unsigned NOT NULL auto_increment,
	  `title` varchar(255) NOT NULL default '',
	  `custom_class` varchar(255) NOT NULL default '',
	  `auto_play` smallint(6) NOT NULL default '1',
	  `auto_play_timeout` varchar(255) NOT NULL default '',
	  `stop_auto` smallint(6) NOT NULL default '1',
	  `navigation` smallint(6) NOT NULL default '1',
	  `pagination` smallint(6) NOT NULL default '1',
	  `loop` smallint(6) NOT NULL default '1',
	  `next_image` varchar(255) NOT NULL default '',
	  `prev_image` varchar(255) NOT NULL default '',
	  `status` smallint(6) NOT NULL default '0',
	  PRIMARY KEY (`slide_id`),
	  KEY `IDX_LOOKBOOK_SLIDE_ID` (`slide_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	
	CREATE TABLE IF NOT EXISTS {$this->getTable('mgs_lookbook_slide_items')} (
	  `item_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `slide_id` int(11) unsigned NOT NULL,
	  `lookbook_id` int(11) unsigned NOT NULL,
	  `position` int(11) NULL,
	  PRIMARY KEY (`item_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");
	
$installer->endSetup();