<?php
class MGS_Mpanel_Model_Source_Layouts
{
    protected $_options = null;

    public function toOptionArray()
    {
		$layout = Mage::getSingleton('page/source_layout')->toOptionArray();
		array_unshift($layout, array('value' => '', 'label' => Mage::helper('catalog')->__('No layout updates')));

        return $layout;
    }
}