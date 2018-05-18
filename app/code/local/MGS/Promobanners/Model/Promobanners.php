<?php

class MGS_Promobanners_Model_Promobanners extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('promobanners/promobanners');
    }
}