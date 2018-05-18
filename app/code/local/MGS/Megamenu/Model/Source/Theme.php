<?php
class MGS_Megamenu_Model_Source_Theme {

	
    public function toOptionArray() {
        return array(
			array('value' => 'default', 'label' => Mage::helper('megamenu')->__('Default')),
			array('value' => 'black-white', 'label' => Mage::helper('megamenu')->__('Black - White')),
			array('value' => 'gray-white', 'label' => Mage::helper('megamenu')->__('Gray - White')),
			array('value' => 'blue','label' => Mage::helper('megamenu')->__('Blue')),
            array('value' => 'red', 'label' => Mage::helper('megamenu')->__('Red')),
			array('value' => 'green', 'label' => Mage::helper('megamenu')->__('Green')),
			array('value' => 'silver', 'label' => Mage::helper('megamenu')->__('Silver')),
			array('value' => 'romantic', 'label' => Mage::helper('megamenu')->__('Romantic')),
        );
    }

}