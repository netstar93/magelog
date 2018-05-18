<?php

class AW_Blog_Block_Manage_Blog_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
    {
		$url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'blog/'.$row->getFeaturedImage();
		$html = '<img src="'.$url.'" height="50" alt=""/>';
		return $html; 
    }
}