<?php
class MGS_Jollyanytheme_Model_System_Config_Source_Footer {

    public function toOptionArray() {
        return array(
			array('value' => 'footer', 'label' => Mage::helper('jollyanytheme')->__('Version 1')),
			array('value' => 'footer2', 'label' => Mage::helper('jollyanytheme')->__('Version 2')),
			array('value' => 'footer3', 'label' => Mage::helper('jollyanytheme')->__('Version 3'))
        );
    }

}