<?php
class MGS_Deals_Block_Deals extends Mage_Core_Block_Template
{
	public function getDeals(){
		$productId = $this->getProductId();
		$deal = Mage::getModel('deals/deals')
			->getCollection()
			->addFieldToFilter('product_id', $productId)
			->addFieldToFilter('status', 2)
			->getFirstItem();
		return $deal;
	}
	
	public function getProduct(){
		return Mage::getModel('catalog/product')->load($this->getProductId());
	}
}