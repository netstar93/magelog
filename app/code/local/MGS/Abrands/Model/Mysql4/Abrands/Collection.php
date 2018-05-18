<?php

class MGS_Abrands_Model_Mysql4_Abrands_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('abrands/abrands');
    }
}