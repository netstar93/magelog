<?php

class MGS_Portfolio_Model_Mysql4_Category extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('portfolio/category', 'category_id');
    }
	
	protected function _beforeDelete(Mage_Core_Model_Abstract $object) {
		$storeTable = Mage::getSingleton('core/resource')->getTableName('mgs_portfolio_category_items');
        $adapter = $this->_getReadAdapter();
        $adapter->delete($storeTable, 'category_id=' . $object->getId());
    }
}