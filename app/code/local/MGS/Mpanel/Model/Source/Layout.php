<?php
class MGS_Mpanel_Model_Source_Layout {

    public function toOptionArray() {
		$layout = array(
			array('value'=>'wide','label'=>Mage::helper('mpanel')->__('Wide')),
			array('value'=>'boxed','label'=>Mage::helper('mpanel')->__('Boxed'))
		);
		
		return $layout;
    }

}