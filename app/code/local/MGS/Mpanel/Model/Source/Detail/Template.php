<?php
class MGS_Mpanel_Model_Source_Detail_Template {

    public function toOptionArray() {
		$layout = array(
			array('value'=>'0','label'=>Mage::helper('mpanel')->__('Product standard layout')),
			array('value'=>'1','label'=>Mage::helper('mpanel')->__('Product gallery thumbnail')),
			array('value'=>'2','label'=>Mage::helper('mpanel')->__('Product with sticky info')),
			array('value'=>'3','label'=>Mage::helper('mpanel')->__('Product with sticky info 2')),
			array('value'=>'4','label'=>Mage::helper('mpanel')->__('Product with vertical thumbnail'))
		);
		
		return $layout;
    }

}