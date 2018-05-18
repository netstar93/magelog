<?php
class MGS_Mpanel_Model_Source_Yposition {

	
    public function toOptionArray() {
        return array(
			array('value' => 'top', 'label' => Mage::helper('mpanel')->__('Top')),
			array('value' => 'bottom', 'label' => Mage::helper('mpanel')->__('Bottom')),
			array('value' => 'middle', 'label' => Mage::helper('mpanel')->__('Middle')),
        );
    }

}