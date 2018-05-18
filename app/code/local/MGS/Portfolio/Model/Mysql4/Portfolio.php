<?php

class MGS_Portfolio_Model_Mysql4_Portfolio extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the portfolio_id refers to the key field in your database table.
        $this->_init('portfolio/portfolio', 'portfolio_id');
    }
	
	protected function _afterSave(Mage_Core_Model_Abstract $object) {
		$categoryTable = Mage::getSingleton('core/resource')->getTableName('mgs_portfolio_category_items');
        $condition = $this->_getWriteAdapter()->quoteInto('portfolio_id = ?', $object->getId());
        $this->_getWriteAdapter()->delete($categoryTable, $condition);
        if (!$object->getData('category')) {
            $object->setData('category', $object->getData('category_id'));
        }
        if (in_array(0, $object->getData('category'))) {
            $object->setData('category', array(0));
        }
        foreach ((array) $object->getData('category') as $category) {
            $categoryArray = array();
            $categoryArray['portfolio_id'] = $object->getId();
            $categoryArray['category_id'] = $category;
            $this->_getWriteAdapter()->insert($categoryTable, $categoryArray);
        }
        return parent::_afterSave($object);
    }
	
	protected function _afterLoad(Mage_Core_Model_Abstract $object) {
		$categoryTable = Mage::getSingleton('core/resource')->getTableName('mgs_portfolio_category_items');
        $select = $this->_getReadAdapter()->select()
                        ->from($categoryTable)
                        ->where('portfolio_id = ?', $object->getId());

        if ($data = $this->_getReadAdapter()->fetchAll($select)) {
            $categoryArray = array();
            foreach ($data as $row) {
                $categoryArray[] = $row['category_id'];
            }
            $object->setData('category_id', $categoryArray);
        }
        return parent::_afterLoad($object);
    }

    protected function _beforeDelete(Mage_Core_Model_Abstract $object) {
		$storeTable = Mage::getSingleton('core/resource')->getTableName('mgs_portfolio_category_items');
        $adapter = $this->_getReadAdapter();
        $adapter->delete($storeTable, 'portfolio_id=' . $object->getId());
    }
}