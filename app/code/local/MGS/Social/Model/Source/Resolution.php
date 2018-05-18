<?php 
class MGS_Social_Model_Source_Resolution
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'low_resolution', 'label'=>Mage::helper('social')->__('Low Resolution')),
			array('value'=>'thumbnail', 'label'=>Mage::helper('social')->__('Thumbnail')),
            array('value'=>'standard_resolution', 'label'=>Mage::helper('social')->__('Standard Resolution'))
        );
    }

}