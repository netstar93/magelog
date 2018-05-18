<?php
class MGS_Jollyanytheme_Model_System_Config_Source_Color{

    public function toOptionArray() {
        return array(
			array('value' => 'blue', 'label' => Mage::helper('jollyanytheme')->__('Blue')),
			array('value' => 'light-blue', 'label' => Mage::helper('jollyanytheme')->__('Light Blue')),
			array('value' => 'green', 'label' => Mage::helper('jollyanytheme')->__('Green')),
			array('value' => 'light-green', 'label' => Mage::helper('jollyanytheme')->__('Light Green')),
			array('value' => 'orange', 'label' => Mage::helper('jollyanytheme')->__('Orange')),
			array('value' => 'purple', 'label' => Mage::helper('jollyanytheme')->__('Purple')),
			array('value' => 'red', 'label' => Mage::helper('jollyanytheme')->__('Red')),
			array('value' => 'tael', 'label' => Mage::helper('jollyanytheme')->__('Tael')),
			array('value' => 'violet', 'label' => Mage::helper('jollyanytheme')->__('Violet')),
			array('value' => 'yellow', 'label' => Mage::helper('jollyanytheme')->__('Yellow')),
        );
    }
}