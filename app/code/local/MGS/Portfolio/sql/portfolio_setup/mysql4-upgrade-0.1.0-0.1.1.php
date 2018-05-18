<?php

$installer = $this;

$installer->startSetup();

$fieldsSql = 'SHOW COLUMNS FROM ' . $this->getTable('mgs_portfolio_items');
$cols = $installer->getConnection()->fetchCol($fieldsSql);

if (!in_array('project_url', $cols))
{
    $installer->run("ALTER TABLE `{$this->getTable('mgs_portfolio_items')}` ADD `project_url` varchar(255) NULL");
}

if (!in_array('client', $cols))
{
    $installer->run("ALTER TABLE `{$this->getTable('mgs_portfolio_items')}` ADD `client` varchar(255) NULL");
}

$installer->endSetup(); 