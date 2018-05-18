<?php
class MGS_Jollyanytheme_Model_System_Config_Source_Tabs{

    public function toOptionArray() {
        return array(
			array('value' => '', 'label' => ''),
			array('value' => 'static', 'label' => Mage::helper('jollyanytheme')->__('Static block')),
			array('value' => 'attribute', 'label' => Mage::helper('jollyanytheme')->__('Product Attribute'))
        );
    }
}