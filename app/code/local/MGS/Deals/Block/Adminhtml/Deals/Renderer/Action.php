<?php

class MGS_Deals_Block_Adminhtml_Deals_Renderer_Action extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
    {
		$status = $row->getStatus();
		$productId = $row->getProductId();
		
		$url = Mage::helper('adminhtml')->getUrl('adminhtml/catalog_product/edit',array('id'=>$productId,'tab'=>'product_info_tabs_group_49', 'deal'=>1));
		if($status == 3){
			$label = Mage::helper('adminhtml')->__('Add new');
		}
		else{
			$label = Mage::helper('adminhtml')->__('Edit');
		}
		
		$html = '<a href="'.$url.'">'.$label.'</a>';
		return $html;
    }
}