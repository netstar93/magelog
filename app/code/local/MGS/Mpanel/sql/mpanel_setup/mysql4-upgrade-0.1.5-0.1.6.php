<?php

$installer = $this;

$installer->startSetup();

$installer->run("
	ALTER TABLE {$this->getTable('mgs_theme_home_blocks')} ADD `block_class` VARCHAR(255) NULL AFTER `block_cols`;
");

$installer->endSetup(); 