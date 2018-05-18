<?php

$installer = $this;

$installer->startSetup();

$installer->run("
	-- DROP TABLE IF EXISTS {$this->getTable('mgs_megamenu_parent')};
	CREATE TABLE {$this->getTable('mgs_megamenu_parent')} (
	  `parent_id` int(11) unsigned NOT NULL auto_increment,
	  `title` varchar(255) NOT NULL default '',
	  `menu_type` smallint(6) NOT NULL default '1',
	  `custom_class` varchar(255) NOT NULL default '',
	  `status` smallint(6) NOT NULL default '0',
	  PRIMARY KEY (`parent_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	
	INSERT INTO `{$this->getTable('mgs_megamenu_parent')}` (`parent_id`, `title`, `menu_type`, `status`) VALUES (1, 'Main Menu', 1, 1);
");

$fieldsSql = 'SHOW COLUMNS FROM ' . $this->getTable('mgs_megamenu');
$cols = $installer->getConnection()->fetchCol($fieldsSql);

if (!in_array('parent_id', $cols))
{
    $installer->run("ALTER TABLE `{$this->getTable('mgs_megamenu')}` ADD `parent_id` int(11) NOT NULL default '1'");
}

if (!in_array('store_id', $cols))
{
    $installer->run("ALTER TABLE `{$this->getTable('mgs_megamenu')}` ADD `store` int(11) NULL");
}

$installer->endSetup(); 