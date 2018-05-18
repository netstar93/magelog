<?php

class MGS_Mpanel_Block_Layout_Product_Left extends Mage_Core_Block_Template {

    public function getBlocks() {
        $productId = Mage::registry('current_product')->getId();
        $collection = Mage::getModel('mpanel/block')
                ->getCollection()
                ->addFieldToFilter('page_type', array('eq' => 'product'))
                ->addFieldToFilter('page_id', array('eq' => $productId))
                ->addFieldToFilter('place', array('eq' => 'left'))
                ->setOrder('sort_order', 'ASC');
        if (!count($collection)) {
            $collection = Mage::getModel('mpanel/block')
                    ->getCollection()
                    ->addFieldToFilter('page_type', array('eq' => 'product'))
                    ->addFieldToFilter('page_id', array('eq' => 0))
                    ->addFieldToFilter('place', array('eq' => 'left'))
                    ->setOrder('sort_order', 'ASC');
        }
        return $collection;
    }

}
