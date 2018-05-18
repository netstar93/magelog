<?php

class MGS_Mpanel_Block_Layout_Brand_Right extends Mage_Core_Block_Template {

    public function getBlocks() {
        $brandId = 0;
        $collection = Mage::getModel('mpanel/block')
                ->getCollection()
                ->addFieldToFilter('page_type', array('eq' => 'brand'))
                ->addFieldToFilter('page_id', array('eq' => $brandId))
                ->addFieldToFilter('place', array('eq' => 'right'))
                ->setOrder('sort_order', 'ASC');
        if (!count($collection)) {
            $collection = Mage::getModel('mpanel/block')
                    ->getCollection()
                    ->addFieldToFilter('page_type', array('eq' => 'brand'))
                    ->addFieldToFilter('page_id', array('eq' => 0))
                    ->addFieldToFilter('place', array('eq' => 'right'))
                    ->setOrder('sort_order', 'ASC');
        }
        return $collection;
    }

}
