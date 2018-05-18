<?php
class MGS_Jollyanytheme_Model_System_Config_Source_Header {

    public function toOptionArray() {
        return array(
			array('value' => 'header', 'label' => Mage::helper('jollyanytheme')->__('Version 1')),
			array('value' => 'header2', 'label' => Mage::helper('jollyanytheme')->__('Version 2')),
			array('value' => 'header3', 'label' => Mage::helper('jollyanytheme')->__('Version 3'))
        );
    }

}