<?php 
class MGS_Mpanel_Model_Source_Searchperrow
{
    public function toOptionArray()
    {
        return array(
			array('value'=>'', 'label'=>Mage::helper('mpanel')->__('Use general setting')),
			array('value'=>2, 'label'=>2),
			array('value'=>3, 'label'=>3),
            array('value'=>4, 'label'=>4),
            array('value'=>5, 'label'=>5),
            array('value'=>6, 'label'=>6),
            array('value'=>7, 'label'=>7),
            array('value'=>8, 'label'=>8)
        );
    }

}