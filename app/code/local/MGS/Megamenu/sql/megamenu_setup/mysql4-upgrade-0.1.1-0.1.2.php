<?php

$installer = $this;

$installer->startSetup();

$fieldsSql = 'SHOW COLUMNS FROM ' . $this->getTable('mgs_megamenu');
$cols = $installer->getConnection()->fetchCol($fieldsSql);

if (!in_array('html_label', $cols))
{
    $installer->run("ALTER TABLE `{$this->getTable('mgs_megamenu')}` ADD `html_label` VARCHAR(255) NULL");
}

$installer->endSetup(); 