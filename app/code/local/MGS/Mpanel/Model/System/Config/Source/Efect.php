<?php
class MGS_Jollyanytheme_Model_System_Config_Source_Efect {

    public function toOptionArray() {
        return array(
			array('value' => 'random', 'label' => Mage::helper('jollyanytheme')->__('Random')),
			array('value' => 'fade', 'label' => Mage::helper('jollyanytheme')->__('Fade')),
			array('value' => 'slideleft', 'label' => Mage::helper('jollyanytheme')->__('Slide Left')),
			array('value' => 'slideright', 'label' => Mage::helper('jollyanytheme')->__('Slide Right')),
			array('value' => 'slideup', 'label' => Mage::helper('jollyanytheme')->__('Slice Up')),
			array('value' => 'slidedown', 'label' => Mage::helper('jollyanytheme')->__('Slice Down')),
			array('value' => 'zoomin', 'label' => Mage::helper('jollyanytheme')->__('Zoom In')),
			array('value' => 'zoomout', 'label' => Mage::helper('jollyanytheme')->__('Zoom Out')),
			array('value' => 'boxslide', 'label' => Mage::helper('jollyanytheme')->__('Box Slide')),
			array('value' => 'boxfade', 'label' => Mage::helper('jollyanytheme')->__('Box Fade')),
			array('value' => 'slotzoom-horizontal', 'label' => Mage::helper('jollyanytheme')->__('Slot Zoom Horizontal')),
			array('value' => 'slotzoom-vertical', 'label' => Mage::helper('jollyanytheme')->__('Slot Zoom Vertical')),
			array('value' => 'slotslide-vertical', 'label' => Mage::helper('jollyanytheme')->__('Slot Slide Vertical')),
			array('value' => 'slotfade-vertical', 'label' => Mage::helper('jollyanytheme')->__('Slot Fade Vertical')),
			array('value' => 'curtain-1', 'label' => Mage::helper('jollyanytheme')->__('Curtain 1')),
			array('value' => 'curtain-2', 'label' => Mage::helper('jollyanytheme')->__('Curtain 2')),
			array('value' => 'curtain-3', 'label' => Mage::helper('jollyanytheme')->__('Curtain 3')),
        );
    }

}