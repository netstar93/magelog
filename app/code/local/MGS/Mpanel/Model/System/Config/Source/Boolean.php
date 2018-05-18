<?php
class MGS_Jollyanytheme_Model_System_Config_Source_Boolean {

    public function toOptionArray() {
        return array(
			array('value' => 'on', 'label' => Mage::helper('jollyanytheme')->__('Yes')),
			array('value' => 'off', 'label' => Mage::helper('jollyanytheme')->__('No'))
        );
    }

}