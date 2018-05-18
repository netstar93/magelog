<?php

$installer = $this;

$installer->startSetup();

$installer->run("
	INSERT INTO `{$this->getTable('mgs_theme_home')}` (`name`, `thumbnail`) VALUES ('3_columns_equal', '3_columns_equal.jpg');
");

$installer->endSetup(); 