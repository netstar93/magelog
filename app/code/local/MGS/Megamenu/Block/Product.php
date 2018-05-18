<?php
class MGS_Megamenu_Block_Product extends Mage_Catalog_Block_Product
    implements Mage_Widget_Block_Interface
{
	public function getProduct(){
		if ($this->getData('product_id')) {
			$productIdString = $this->getData('product_id');
			$arrExplode = explode('/', $productIdString);
			if(isset($arrExplode[1])){
				$product = Mage::getModel('catalog/product')->load($arrExplode[1]);
				if($product->getId()){
					return $product;
				}
				else{
					return false;
				}
			}
			else{
				return false;
			}
		}
		return false;
	}
	
	protected function _toHtml()
    {
        if ($this->getProduct()) {
            return parent::_toHtml();
        }
        return '';
    }
}