<?php

class MGS_Portfolio_Model_Mysql4_Stores_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('portfolio/stores');
    }
}