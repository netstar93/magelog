<?php
class MGS_Mpanel_Model_Source_Repeat {

	
    public function toOptionArray() {
        return array(
			array('value' => 'repeat', 'label' => Mage::helper('mpanel')->__('Repeat')),
			array('value' => 'repeat-x', 'label' => Mage::helper('mpanel')->__('Repeat X')),
			array('value' => 'repeat-y', 'label' => Mage::helper('mpanel')->__('Repeat Y')),
			array('value' => 'no-repeat', 'label' => Mage::helper('mpanel')->__('No Repeat')),
        );
    }

}