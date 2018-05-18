<?php

/* * ****************************************************
 * Package   : Event
 * Author    : HIEPNH
 * Copyright : (c) 2014
 * ***************************************************** */
?>
<?php

class MGS_Event_Block_Event extends Mage_Core_Block_Template {

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function getCollection() {
        $collection = Mage::getModel('event/event')->getCollection()
                ->addFieldToFilter('status', array('eq' => 1));
        $collection->addStoreFilter(Mage::app()->getStore()->getStoreId());
        return $collection;
    }

}
