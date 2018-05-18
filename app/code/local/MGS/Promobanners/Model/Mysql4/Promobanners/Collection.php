<?php

class MGS_Promobanners_Model_Mysql4_Promobanners_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('promobanners/promobanners');
    }
}