<?php

$installer = $this;

$installer->startSetup();

$fieldsSql = 'SHOW COLUMNS FROM ' . $this->getTable('mgs_theme_layout');
$cols = $installer->getConnection()->fetchCol($fieldsSql);

if (!in_array('indentifier', $cols))
{
    $installer->run("ALTER TABLE `{$this->getTable('mgs_theme_layout')}` ADD `indentifier` int NOT NULL default '0'");
}

if (!in_array('left', $cols))
{
    $installer->run("ALTER TABLE `{$this->getTable('mgs_theme_layout')}` ADD `left` TEXT NULL");
}

if (!in_array('right', $cols))
{
    $installer->run("ALTER TABLE `{$this->getTable('mgs_theme_layout')}` ADD `right` TEXT NULL");
}

$installer->endSetup(); 