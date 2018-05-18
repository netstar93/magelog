<?php
class MGS_Mpanel_Model_Source_Breadcrumb {

    public function toOptionArray() {
		$width = array(
			array('value'=>'image','label'=>Mage::helper('mpanel')->__('Image')),
			array('value'=>'color','label'=>Mage::helper('mpanel')->__('Color'))
		);
		return $width;
    }

}