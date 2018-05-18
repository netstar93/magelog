<?php
class MGS_Mpanel_Block_Product_Special extends Mage_Catalog_Block_Product_Abstract
{
 
    public function getProduct()
    {
		$sku = $this->getSku();
		$product = Mage::getModel('catalog/product')
			->getCollection()
			->addStoreFilter(Mage::app()->getStore()->getId())
			->addAttributeToSelect('*')
			->addAttributeToFilter('sku', $sku)
			->addAttributeToFilter('status',1)
			->addAttributeToFilter('visibility',array('neq'=>1))
			->getFirstItem();
		return $product;
    }
}
