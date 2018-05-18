<?php

class MGS_Megamenu_Model_Mysql4_Megamenu extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the megamenu_id refers to the key field in your database table.
        $this->_init('megamenu/megamenu', 'megamenu_id');
    }
	
	protected function _beforeSave(Mage_Core_Model_Abstract $object) {
        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
        if (!$object->getFromDate()) {
            $object->setFromDate(date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time())));
        } else {
            $object->setFromDate(Mage::app()->getLocale()->date($object->getFromDate(), $dateFormatIso));
            $object->setFromDate($object->getFromDate()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));
            $object->setFromDate(Mage::getSingleton('core/date')->gmtDate(null, $object->getFromDate()));
        }
        if (!$object->getToDate()) {
            $object->setToDate();
        } else {
            $object->setToDate(Mage::app()->getLocale()->date($object->getToDate(), $dateFormatIso));
            $object->setToDate($object->getToDate()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));
            $object->setToDate(Mage::getSingleton('core/date')->gmtDate(null, $object->getToDate()));
        }
        return $this;
    }

    protected function _afterSave(Mage_Core_Model_Abstract $object) {
		$storeTable = Mage::getSingleton('core/resource')->getTableName('mgs_megamenu_store');
        $condition = $this->_getWriteAdapter()->quoteInto('megamenu_id = ?', $object->getId());
        $this->_getWriteAdapter()->delete($storeTable, $condition);
        if (!$object->getData('stores')) {
            $object->setData('stores', $object->getData('store_id'));
        }
        if (in_array(0, $object->getData('stores'))) {
            $object->setData('stores', array(0));
        }
        foreach ((array) $object->getData('stores') as $store) {
            $storeArray = array();
            $storeArray['megamenu_id'] = $object->getId();
            $storeArray['store_id'] = $store;
            $this->_getWriteAdapter()->insert($storeTable, $storeArray);
        }
        return parent::_afterSave($object);
    }
    protected function _afterLoad(Mage_Core_Model_Abstract $object) {
		$storeTable = Mage::getSingleton('core/resource')->getTableName('mgs_megamenu_store');
        $select = $this->_getReadAdapter()->select()
                        ->from($storeTable)
                        ->where('megamenu_id = ?', $object->getId());

        if ($data = $this->_getReadAdapter()->fetchAll($select)) {
            $storesArray = array();
            foreach ($data as $row) {
                $storesArray[] = $row['store_id'];
            }
            $object->setData('store_id', $storesArray);
        }
        return parent::_afterLoad($object);
    }

    protected function _beforeDelete(Mage_Core_Model_Abstract $object) {
		$storeTable = Mage::getSingleton('core/resource')->getTableName('mgs_megamenu_store');
        $adapter = $this->_getReadAdapter();
        $adapter->delete($storeTable, 'megamenu_id=' . $object->getId());
    }
}