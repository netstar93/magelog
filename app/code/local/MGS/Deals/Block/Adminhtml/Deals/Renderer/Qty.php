<?php

class MGS_Deals_Block_Adminhtml_Deals_Renderer_Qty extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
    {
		if($row->getDealQty()==''){
			return Mage::helper('adminhtml')->__('Unlimited');
		}
    	return $row->getDealQty();
    }
}