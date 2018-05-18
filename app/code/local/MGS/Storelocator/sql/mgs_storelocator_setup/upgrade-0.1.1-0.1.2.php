<?php
/**
 * Storelocator installation script
 * 
 * @category    MGS
 * @package     MGS_Storelocator
 * @author      MGS Magento Team <magento@mgstechnologies.co.in>
 * 
 */
/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

//Add unique index for column 'name'
$installer->getConnection()->addIndex(
        $installer->getTable('mgs_storelocator/storelocator'),
        $installer->getIdxName(
                'mgs_storelocator/storelocator', 
                array('name'), 
                Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
         ),
        array('name'), 
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);

$installer->endSetup();