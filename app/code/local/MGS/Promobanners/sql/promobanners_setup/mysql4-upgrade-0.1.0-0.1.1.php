<?php

$installer = $this;

$installer->startSetup();

$fieldsSql = 'SHOW COLUMNS FROM ' . $this->getTable('mgs_promobanners');
$cols = $installer->getConnection()->fetchCol($fieldsSql);

if (!in_array('button', $cols))
{
    $installer->run("ALTER TABLE `{$this->getTable('mgs_promobanners')}` ADD `button` varchar(255) NULL");
}
$installer->endSetup(); 