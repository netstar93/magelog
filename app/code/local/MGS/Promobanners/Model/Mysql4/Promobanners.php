<?php

class MGS_Promobanners_Model_Mysql4_Promobanners extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the promobanners_id refers to the key field in your database table.
        $this->_init('promobanners/promobanners', 'promobanners_id');
    }
}