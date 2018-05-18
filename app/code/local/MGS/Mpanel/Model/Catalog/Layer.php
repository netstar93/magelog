<?php
class MGS_Mpanel_Model_Catalog_Layer extends Mage_Catalog_Model_Layer
{
    public function getProductCollection()
    {
        if (isset($this->_productCollections[$this->getCurrentCategory()->getId()])) {
            $collection = $this->_productCollections[$this->getCurrentCategory()->getId()];
        } else {
            $collection = $this->getCurrentCategory()->getProductCollection();
            $this->prepareProductCollection($collection);
            $this->_productCollections[$this->getCurrentCategory()->getId()] = $collection;
        }
		
		$this->currentRate = Mage::app()->getStore()->getCurrentCurrencyRate();;
		$max=$this->getMaxPriceFilter();
		$min=$this->getMinPriceFilter();
		
		if($min && $max){
			$collection->getSelect()->where('final_price BETWEEN '.$min.' AND '.$max);
		}
		
        return $collection;
    }
	
	
	public function getMaxPriceFilter(){
		if($max = Mage::app()->getRequest()->getParam('max')){
			return $max/$this->currentRate;
		}
	}
	
	public function getMinPriceFilter(){
		if($min = Mage::app()->getRequest()->getParam('min')){
			return $min/$this->currentRate;
		}
	}
    
	
}
