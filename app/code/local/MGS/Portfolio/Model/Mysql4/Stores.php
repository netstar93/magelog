<?php

class MGS_Portfolio_Model_Mysql4_Stores extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('portfolio/stores', 'entity_id');
    }
	
}