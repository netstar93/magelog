<?php 
class MGS_Mpanel_Model_Source_Searchratio
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'', 'label'=>Mage::helper('mpanel')->__('Use general setting')),
            array('value'=>'1', 'label'=>Mage::helper('mpanel')->__('1/1 Square')),
			array('value'=>'2', 'label'=>Mage::helper('mpanel')->__('1/2 Portrait')),
			array('value'=>'3', 'label'=>Mage::helper('mpanel')->__('2/3 Portrait')),
            array('value'=>'4', 'label'=>Mage::helper('mpanel')->__('3/4 Portrait')),
            array('value'=>'5', 'label'=>Mage::helper('mpanel')->__('2/1 Landscape')),
            array('value'=>'6', 'label'=>Mage::helper('mpanel')->__('3/2 Landscape')),
            array('value'=>'7', 'label'=>Mage::helper('mpanel')->__('4/3 Landscape'))
        );
    }

}