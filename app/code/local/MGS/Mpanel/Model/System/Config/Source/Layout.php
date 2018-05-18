<?php
class MGS_Jollyanytheme_Model_System_Config_Source_Layout {

    public function toOptionArray() {
        return array(
			array('value' => 'wide', 'label' => Mage::helper('jollyanytheme')->__('Wide')),
			array('value' => 'boxed', 'label' => Mage::helper('jollyanytheme')->__('Boxed'))
        );
    }

}