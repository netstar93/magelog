<?php
class MGS_Mpanel_Model_Source_Header {

    public function toOptionArray() {
        return Mage::helper('mpanel')->getHeaderVersion();
    }

}