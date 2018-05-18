<?php

class MGS_Abrands_Block_Adminhtml_Abrands_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
    {
		$url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'abrands/'.$row->getFilename();
		$html = '<img src="'.$url.'" height="35" alt=""/>';
		return $html; 
    }
}