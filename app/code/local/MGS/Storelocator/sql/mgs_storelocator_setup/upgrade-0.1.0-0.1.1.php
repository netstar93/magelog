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

//Change field store_id to storelocator_id
$installer->getConnection()->changeColumn($installer->getTable('mgs_storelocator/storelocator'), 'store_id', 'storelocator_id', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'identity'  => true,
    'unsigned'  => true,
    'nullable'  => false,
    'primary'   => true,
    'comment'   => 'Storelocator Id'
));

/**
 * Create table 'mgs_storelocator/storelocator_store'
 */

$table = $installer->getConnection()
    ->newTable($installer->getTable('mgs_storelocator/storelocator_store'))
    ->addColumn('storelocator_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
        'primary'   => true,
        ), 'Storelocator ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Store ID')
    ->addIndex($installer->getIdxName('mgs_storelocator/storelocator_store', array('store_id')),
        array('store_id'))
        
    ->addForeignKey($installer->getFkName('mgs_storelocator/storelocator_store', 'storelocator_id', 'mgs_storelocator/storelocator', 'storelocator_id'),
        'storelocator_id', $installer->getTable('mgs_storelocator/storelocator'), 'storelocator_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
        
    ->addForeignKey($installer->getFkName('mgs_storelocator/storelocator_store', 'store_id', 'core/store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
        
    ->setComment('MGS Storelocator To Store Linkage Table');
$installer->getConnection()->createTable($table);

$installer->endSetup();