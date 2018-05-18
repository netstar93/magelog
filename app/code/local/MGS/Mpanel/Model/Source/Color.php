<?php
class MGS_Mpanel_Model_Source_Color {

	
    public function toOptionArray() {
        return array(
			array('value' => 'blue', 'label' => Mage::helper('mpanel')->__('Blue')),
			array('value' => 'light-blue', 'label' => Mage::helper('mpanel')->__('Light Blue')),
			array('value' => 'green', 'label' => Mage::helper('mpanel')->__('Green')),
			array('value' => 'light-green', 'label' => Mage::helper('mpanel')->__('Light Green')),
			array('value' => 'orange', 'label' => Mage::helper('mpanel')->__('Orange')),
			array('value' => 'purple', 'label' => Mage::helper('mpanel')->__('Purple')),
			array('value' => 'red', 'label' => Mage::helper('mpanel')->__('Red')),
			array('value' => 'tael', 'label' => Mage::helper('mpanel')->__('Tael')),
			array('value' => 'violet', 'label' => Mage::helper('mpanel')->__('Violet')),
			array('value' => 'yellow', 'label' => Mage::helper('mpanel')->__('Yellow')),
        );
    }

}