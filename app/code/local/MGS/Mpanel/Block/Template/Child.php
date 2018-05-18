<?php
class MGS_Mpanel_Block_Template_Child extends Mage_Core_Block_Template
{
	public function getChildBlocks(){
		$storeId = Mage::app()->getStore()->getId();
		$templateLayout = $this->getTemplateLayout();
		$blockName = $this->getBlockName();
		
		$childs =  Mage::getModel('mpanel/childs')
			->getCollection()
			->addFieldToFilter('store_id', $storeId)
			->addFieldToFilter('block_name', $blockName)
			->addFieldToFilter('home_name', $templateLayout)
			->setOrder('position', 'ASC');
		return $childs;
	}
}