<?php

$installer = $this;

$installer->startSetup();

$fieldsSql = 'SHOW COLUMNS FROM ' . $this->getTable('mgs_portfolio_category');
$cols = $installer->getConnection()->fetchCol($fieldsSql);

if (!in_array('identifier', $cols))
{
    $installer->run("ALTER TABLE `{$this->getTable('mgs_portfolio_category')}` ADD `identifier` varchar(255) NULL");
}


$installer->endSetup(); 