<?php

class MGS_Event_Block_Widget extends Mage_Core_Block_Template {

    public function getCollection() {
        $collection = Mage::getModel('event/event')->getCollection()
                ->addFieldToFilter('status', array('eq' => 1));
        $collection->addStoreFilter(Mage::app()->getStore()->getStoreId());
        if ($this->getEventCount() && ($this->getEventCount() > 0)) {
            $collection->setPageSize($this->getEventCount());
        }
        return $collection;
    }

}
