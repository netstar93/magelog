<?php
class MGS_Megamenu_Block_Horizontal extends MGS_Megamenu_Block_Abstract
{
	public function getMegamenuItems(){
		$store = Mage::app()->getStore();
		$menuCollection = Mage::getModel('megamenu/megamenu')
			->getCollection()
			->addStoreFilter($store)
			->addFieldToFilter('parent_id', $this->getMenuId())
			->addFieldToFilter('status', 1)
			->setOrder('position', 'ASC')
		;
		return $menuCollection;
	}
}