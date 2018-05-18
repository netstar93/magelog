<?php

class MGS_Deals_Model_Deals extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('deals/deals');
    }
}