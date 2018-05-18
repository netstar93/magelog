<?php

$installer = $this;

$installer->startSetup();

$fieldsSql = 'SHOW COLUMNS FROM ' . $this->getTable('mgs_promobanners');
$cols = $installer->getConnection()->fetchCol($fieldsSql);

if (!in_array('text_align', $cols))
{
    $installer->run("ALTER TABLE `{$this->getTable('mgs_promobanners')}` ADD `text_align` varchar(255) NULL");
}
$installer->endSetup(); 