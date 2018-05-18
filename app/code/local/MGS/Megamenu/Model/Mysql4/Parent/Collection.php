<?php

class MGS_Megamenu_Model_Mysql4_Parent_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('megamenu/parent');
    }
}