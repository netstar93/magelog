<?php

class MGS_Brand_Block_Homeblock extends Mage_Core_Block_Template {
    public function getCollection() {
		$brandIds = explode(',',$this->getBrandIds());
        $collection = Mage::getModel('brand/brand')->getCollection()
                ->addFieldToFilter('status', array('eq' => 1))
                ->addFieldToFilter('id', array('in' => $brandIds))
                ->setOrder('priority', 'asc');
        $collection->addStoreFilter(Mage::app()->getStore()->getStoreId());
        return $collection;
    }

    public function getUrlKey() {
        $helper = Mage::helper('brand');
        if ($helper->urlKey() != '') {
            $urlKey = Mage::helper('brand')->urlKey();
        } else {
            $urlKey = 'brand';
        }
        return $urlKey;
    }

}
