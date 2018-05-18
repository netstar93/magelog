<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

class MGS_Brand_Model_Mysql4_Brand extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {
        // Note that the id refers to the key field in your database table.
        $this->_init('brand/brand', 'id');
    }

    protected function _afterLoad(Mage_Core_Model_Abstract $object) {
        if (!$object->getIsMassDelete()) {
            $object = $this->__loadStore($object);
        }
        return parent::_afterLoad($object);
    }

    protected function _afterSave(Mage_Core_Model_Abstract $object) {
        if (!$object->getIsMassStatus()) {
            $this->__saveToStoreTable($object);
        }
        return parent::_afterSave($object);
    }

    protected function _beforeDelete(Mage_Core_Model_Abstract $object) {
        $adapter = $this->_getReadAdapter();
        $adapter->delete($this->getTable('brand/store'), 'brand_id=' . $object->getId());
        return parent::_beforeDelete($object);
    }

    protected function _getLoadSelect($field, $value, $object) {
        $select = parent::_getLoadSelect($field, $value, $object);
        if ($object->getStoreId()) {
            $select->join(array('store_table' => $this->getTable('brand/store')), $this->getMainTable() . '.id = store_table.brand_id')
                    ->where('store_table.store_id in (0, ?) ', $object->getStoreId())
                    ->order('store_id DESC')
                    ->limit(1);
        }
        return $select;
    }

    private function __loadStore(Mage_Core_Model_Abstract $object) {
        $select = $this->_getReadAdapter()->select()
                ->from($this->getTable('brand/store'))
                ->where('brand_id = ?', $object->getId());
        if ($data = $this->_getReadAdapter()->fetchAll($select)) {
            $array = array();
            foreach ($data as $row) {
                $array[] = $row['store_id'];
            }
            $object->setData('stores', $array);
        }
        return $object;
    }

    private function __saveToStoreTable(Mage_Core_Model_Abstract $object) {
        if (!$object->getData('stores')) {
            $condition = $this->_getWriteAdapter()->quoteInto('brand_id = ?', $object->getId());
            $this->_getWriteAdapter()->delete($this->getTable('brand/store'), $condition);
            $storeArray = array(
                'brand_id' => $object->getId(),
                'store_id' => '0');
            $this->_getWriteAdapter()->insert($this->getTable('brand/store'), $storeArray);
            return true;
        }
        $condition = $this->_getWriteAdapter()->quoteInto('brand_id = ?', $object->getId());
        $this->_getWriteAdapter()->delete($this->getTable('brand/store'), $condition);
        foreach ((array) $object->getData('stores') as $store) {
            $storeArray = array();
            $storeArray['brand_id'] = $object->getId();
            $storeArray['store_id'] = $store;
            $this->_getWriteAdapter()->insert($this->getTable('brand/store'), $storeArray);
        }
    }

}
