<?php

class MGS_Mpanel_Model_Mysql4_Blocks extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the block_id refers to the key field in your database table.
        $this->_init('mpanel/blocks', 'block_id');
    }
}