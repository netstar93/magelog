<?php
class MGS_Mpanel_Model_Source_Background {

    public function toOptionArray() {
        return Mage::helper('mpanel')->getBackgroundPattern();
    }

}