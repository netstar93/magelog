<?php

class MGS_Deals_Model_Mysql4_Deals_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('deals/deals');
    }
}