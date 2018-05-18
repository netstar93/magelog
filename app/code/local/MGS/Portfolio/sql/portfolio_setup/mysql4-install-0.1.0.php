<?php

$installer = $this;

$installer->startSetup();

$installer->run("

CREATE TABLE {$this->getTable('mgs_portfolio_category')} (
  `category_id` int(11) unsigned NOT NULL auto_increment,
  `category_name` varchar(255) NOT NULL default '',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE {$this->getTable('mgs_portfolio_items')} (
  `portfolio_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `thumbnail_image` varchar(255) NOT NULL default '',
  `base_image` varchar(255) NOT NULL default '',
  `services` varchar(255) NOT NULL default '',
  `skills` varchar(255) NOT NULL default '',
  `description` text NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`portfolio_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE {$this->getTable('mgs_portfolio_category_items')} (
  `entity_id` int(11) unsigned NOT NULL auto_increment,
  `category_id` int(11) NULL,
  `portfolio_id` int(11) NULL,
  PRIMARY KEY (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");

$installer->endSetup(); 