<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();
$installer->run("
    ALTER TABLE {$this->getTable('mgs_qa_topics')} ADD `store_id` int(11) NOT NULL default '0' AFTER `status`;
    ALTER TABLE {$this->getTable('mgs_qa_questions')} ADD `store_id` int(11) NOT NULL default '0' AFTER `score`;
");
$installer->endSetup();
