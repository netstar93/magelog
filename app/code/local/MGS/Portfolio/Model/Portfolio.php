<?php

class MGS_Portfolio_Model_Portfolio extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('portfolio/portfolio');
    }
}