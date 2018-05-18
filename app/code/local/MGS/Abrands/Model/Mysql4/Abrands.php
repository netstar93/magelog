<?php

class MGS_Abrands_Model_Mysql4_Abrands extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the abrands_id refers to the key field in your database table.
        $this->_init('abrands/abrands', 'abrands_id');
    }
}