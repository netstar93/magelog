<?php
class MGS_Testimonial_Model_Source_Amount {
    public function toOptionArray() {
        return array(
			array('value' => '', 'label' => Mage::helper('mpanel')->__('')),
			array('value' => '5', 'label' => Mage::helper('mpanel')->__('5')),
			array('value' => '10', 'label' => Mage::helper('mpanel')->__('10')),
			array('value' => '15', 'label' => Mage::helper('mpanel')->__('15')),
        );
    }

}