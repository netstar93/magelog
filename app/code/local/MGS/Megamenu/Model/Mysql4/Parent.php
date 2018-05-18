<?php

class MGS_Megamenu_Model_Mysql4_Parent extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the megamenu_id refers to the key field in your database table.
        $this->_init('megamenu/parent', 'parent_id');
    }
}