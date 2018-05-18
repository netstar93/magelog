<?php

class MGS_Mpanel_Block_Layout_Search_Left extends Mage_Core_Block_Template {

    public function getBlocks() {
        $collection = Mage::getModel('mpanel/block')
                ->getCollection()
                ->addFieldToFilter('page_type', array('eq' => 'search'))
                ->addFieldToFilter('page_id', array('eq' => 0))
                ->addFieldToFilter('place', array('eq' => 'left'))
                ->setOrder('sort_order', 'ASC');
        return $collection;
    }

}
