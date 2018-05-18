<?php

class MGS_Mpanel_Model_Mysql4_Store extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the home_store_id refers to the key field in your database table.
        $this->_init('mpanel/store', 'home_store_id');
    }
}