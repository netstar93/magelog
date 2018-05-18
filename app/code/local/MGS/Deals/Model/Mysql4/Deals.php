<?php

class MGS_Deals_Model_Mysql4_Deals extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the deals_id refers to the key field in your database table.
        $this->_init('deals/deals', 'deals_id');
    }
}