<?php

class MGS_Mpanel_Block_Layout_Category_Right extends Mage_Core_Block_Template {

    public function getBlocks() {
        $categoryId = Mage::registry('current_category')->getId();
        $collection = Mage::getModel('mpanel/block')
                ->getCollection()
                ->addFieldToFilter('page_type', array('eq' => 'category'))
                ->addFieldToFilter('page_id', array('eq' => $categoryId))
                ->addFieldToFilter('place', array('eq' => 'right'))
                ->setOrder('sort_order', 'ASC');
        if (!count($collection)) {
            $collection = Mage::getModel('mpanel/block')
                    ->getCollection()
                    ->addFieldToFilter('page_type', array('eq' => 'category'))
                    ->addFieldToFilter('page_id', array('eq' => 0))
                    ->addFieldToFilter('place', array('eq' => 'right'))
                    ->setOrder('sort_order', 'ASC');
        }
        return $collection;
    }

}
