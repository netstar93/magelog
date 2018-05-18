<?php

class MGS_Megamenu_Model_Parent extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('megamenu/parent');
    }
}