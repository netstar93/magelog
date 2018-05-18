<?php
class MGS_Mpanel_Model_Source_Style {

    public function toOptionArray() {
		$layout = array(
			array('value'=>'light','label'=>Mage::helper('mpanel')->__('Light')),
			array('value'=>'dark','label'=>Mage::helper('mpanel')->__('Dark'))
		);
		
		return $layout;
    }

}