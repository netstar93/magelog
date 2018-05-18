<?php

/* * ****************************************************
 * Package   : Event
 * Author    : HIEPNH
 * Copyright : (c) 2014
 * ***************************************************** */
?>
<?php

class MGS_Event_Model_Mysql4_Event extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {
        // Note that the id refers to the key field in your database table.
        $this->_init('event/event', 'id');
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
        $adapter->delete($this->getTable('event/store'), 'event_id=' . $object->getId());
        return parent::_beforeDelete($object);
    }

    protected function _getLoadSelect($field, $value, $object) {
        $select = parent::_getLoadSelect($field, $value, $object);
        if ($object->getStoreId()) {
            $select->join(array('store_table' => $this->getTable('event/store')), $this->getMainTable() . '.id = store_table.event_id')
                    ->where('store_table.store_id in (0, ?) ', $object->getStoreId())
                    ->order('store_id DESC')
                    ->limit(1);
        }
        return $select;
    }

    private function __loadStore(Mage_Core_Model_Abstract $object) {
        $select = $this->_getReadAdapter()->select()
                ->from($this->getTable('event/store'))
                ->where('event_id = ?', $object->getId());
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
            $condition = $this->_getWriteAdapter()->quoteInto('event_id = ?', $object->getId());
            $this->_getWriteAdapter()->delete($this->getTable('event/store'), $condition);
            $storeArray = array(
                'event_id' => $object->getId(),
                'store_id' => '0');
            $this->_getWriteAdapter()->insert($this->getTable('event/store'), $storeArray);
            return true;
        }
        $condition = $this->_getWriteAdapter()->quoteInto('event_id = ?', $object->getId());
        $this->_getWriteAdapter()->delete($this->getTable('event/store'), $condition);
        foreach ((array) $object->getData('stores') as $store) {
            $storeArray = array();
            $storeArray['event_id'] = $object->getId();
            $storeArray['store_id'] = $store;
            $this->_getWriteAdapter()->insert($this->getTable('event/store'), $storeArray);
        }
    }

}
