<?php
class MGS_Mpanel_Model_Source_Width {

    public function toOptionArray() {
		$width = array(
			array('value'=>'','label'=>Mage::helper('mpanel')->__('Default (1170px)')),
			array('value'=>'width1024','label'=>Mage::helper('mpanel')->__('1024px')),
			array('value'=>'width1200','label'=>Mage::helper('mpanel')->__('1200px')),
			array('value'=>'width1366','label'=>Mage::helper('mpanel')->__('1366px')),
			array('value'=>'fullwidth','label'=>Mage::helper('mpanel')->__('Full width'))
		);
		
		return $width;
    }

}