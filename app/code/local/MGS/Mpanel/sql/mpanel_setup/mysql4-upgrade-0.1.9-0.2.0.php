<?php

$installer = $this;

$installer->startSetup();

$installer->run("
	ALTER TABLE {$this->getTable('mgs_theme_home_blocks')} ADD `background_cover` smallint(6) NULL default '0' AFTER `background_repeat`;
");

$installer->endSetup(); 