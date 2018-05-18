<?php
class MGS_Profiles_Model_Mysql4_Profile extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("profiles/profile", "profile_id");
    }
}