<?php
class MGS_Mpanel_Model_Source_Footer {

    public function toOptionArray() {
        return Mage::helper('mpanel')->getFooterVersion();
    }

}