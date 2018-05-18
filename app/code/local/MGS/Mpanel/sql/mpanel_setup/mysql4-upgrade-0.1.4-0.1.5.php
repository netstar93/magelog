<?php

$installer = $this;

$installer->startSetup();

$installer->run("
	ALTER TABLE {$this->getTable('mgs_theme_home_blocks')} ADD `block_cols` VARCHAR(255) NULL AFTER `theme_name`;
	
	DELETE FROM `{$this->getTable('mgs_theme_home')}` WHERE `name` IN ('1_column_1', '1_column_2', '2_columns_left_1', '2_columns_left_2', '2_columns_right_1', '2_columns_right_2', '3_columns_1', '3_columns_2', '3_columns_equal');
");

$installer->endSetup(); 