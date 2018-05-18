<?php

$installer = $this;

$installer->startSetup();

$sql = 'select * FROM ' . $this->getTable('permission_block') . ' where block_name = "newsletter/subscribe"';
$newsletterRecord = $installer->getConnection()->fetchRow($sql);

if (!$newsletterRecord)
{
    $installer->run("INSERT INTO {$this->getTable('permission_block')} (`block_name` ,`is_allowed`) VALUES ('newsletter/subscribe', 1);");
}

$sql = 'select * FROM ' . $this->getTable('permission_block') . ' where block_name = "catalog/navigation"';
$navRecord = $installer->getConnection()->fetchRow($sql);

if (!$navRecord)
{
    $installer->run("INSERT INTO {$this->getTable('permission_block')} (`block_name` ,`is_allowed`) VALUES ('catalog/navigation', 1);");
}

$sql = 'select * FROM ' . $this->getTable('permission_block') . ' where block_name = "page/html_topmenu"';
$menuRecord = $installer->getConnection()->fetchRow($sql);

if (!$menuRecord)
{
    $installer->run("INSERT INTO {$this->getTable('permission_block')} (`block_name` ,`is_allowed`) VALUES ('page/html_topmenu', 1);");
}

$installer->endSetup(); 