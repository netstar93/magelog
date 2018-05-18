<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();
$installer->run("
    ALTER TABLE {$this->getTable('blog/blog')} ADD `image` VARCHAR(255) NULL AFTER `featured_image`;
");
$installer->endSetup();
