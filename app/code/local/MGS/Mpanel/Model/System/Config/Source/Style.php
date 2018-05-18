<?php
class MGS_Jollyanytheme_Model_System_Config_Source_Style {

    public function toOptionArray() {
        return array(
			array('value' => 'light-style', 'label' => Mage::helper('jollyanytheme')->__('Light')),
			array('value' => 'dark-style', 'label' => Mage::helper('jollyanytheme')->__('Dark'))
        );
    }

}