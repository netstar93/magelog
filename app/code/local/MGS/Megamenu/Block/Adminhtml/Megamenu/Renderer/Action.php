<?php

class MGS_Megamenu_Block_Adminhtml_Megamenu_Renderer_Action extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
    {
		$id = $row->getId();
		$arrParam = array('id'=>$id);
		if($row->getStore()!=''){
			$arrParam['store'] = $row->getStore();
		}
		$url=$this->getUrl('*/*/edit', $arrParam);
		return sprintf("<a href='%s'>%s</a>", $url, Mage::helper('catalog')->__('Edit'));
    }
}