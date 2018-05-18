<?php
class MGS_Mpanel_Model_Source_Xposition {

	
    public function toOptionArray() {
        return array(
			array('value' => 'left', 'label' => Mage::helper('mpanel')->__('Left')),
			array('value' => 'right', 'label' => Mage::helper('mpanel')->__('Right')),
			array('value' => 'center', 'label' => Mage::helper('mpanel')->__('Center')),
        );
    }

}