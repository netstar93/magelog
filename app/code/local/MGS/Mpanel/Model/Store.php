<?php

class MGS_Mpanel_Model_Store extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mpanel/store');
    }
}