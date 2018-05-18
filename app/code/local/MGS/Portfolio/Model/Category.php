<?php

class MGS_Portfolio_Model_Category extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('portfolio/category');
    }
}