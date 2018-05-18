<?php

class MGS_Abrands_Model_Abrands extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('abrands/abrands');
    }
}