<?php

$installer = $this;

$installer->startSetup();

$installer->run("
	INSERT INTO `{$this->getTable('mgs_theme_home')}` (`name`, `thumbnail`) VALUES ('1_column_full', '1_column_full.jpg');
");

$installer->endSetup(); 