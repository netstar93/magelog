<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();
$installer->run("
    ALTER TABLE {$this->getTable('blog/blog')} ADD `featured_image` VARCHAR(255) NULL AFTER `short_content`;
");
$installer->endSetup();
