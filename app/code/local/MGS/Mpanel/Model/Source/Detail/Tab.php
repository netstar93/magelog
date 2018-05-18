<?php
class MGS_Mpanel_Model_Source_Detail_Tab {

    public function toOptionArray() {
		$layout = array(
			array('value'=>'1','label'=>Mage::helper('mpanel')->__('Tab')),
			array('value'=>'2','label'=>Mage::helper('mpanel')->__('Accordion After Product Detail')),
			array('value'=>'3','label'=>Mage::helper('mpanel')->__('Accordion After Product Information'))
		);
		
		return $layout;
    }

}