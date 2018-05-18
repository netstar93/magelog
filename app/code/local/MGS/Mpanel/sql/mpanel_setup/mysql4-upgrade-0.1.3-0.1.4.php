<?php

$installer = $this;

$installer->startSetup();

$installer->run("
	ALTER TABLE {$this->getTable('mgs_theme_home_block_childs')} ADD `block_content` TEXT NULL AFTER `static_block_id`;
");

$installer->endSetup(); 