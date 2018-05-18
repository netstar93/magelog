<?php

class MGS_Mpanel_Model_Mysql4_Setting extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the layout_id refers to the key field in your database table.
        $this->_init('mpanel/setting', 'category_id');
		$this->_isPkAutoIncrement = false;
    }
}