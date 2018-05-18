<?php

class MGS_Deals_Model_Quote extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    public function __construct()
    {
		if(Mage::getStoreConfig('deals/general/enabled')){
			$this->setCode('mgs_deals');
		}
    }
	
	public function getLabel()
    {
		if(Mage::getStoreConfig('deals/general/enabled')){
			return Mage::helper('deals')->__('Deals Discount&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
		}
    }
	
	public function getAmount(){
		if(Mage::getStoreConfig('deals/general/enabled')){
			$quote = Mage::getSingleton('checkout/session')->getQuote();
			$cartItems = $quote->getAllVisibleItems();
			$amount = 0;
			foreach ($cartItems as $item) {
				$productId = $item->getProductId();
				$qty = $item->getQty();
				
				$deal = Mage::getModel('deals/deals')
					->getCollection()
					->addFieldToFilter('product_id', $productId)
					->addFieldToFilter('status', 2)
					->getFirstItem();
				if($deal->getId()){
					$dealQty = $deal->getMaxDealQty();
					if($dealQty!=''){
						if($qty>$dealQty){
							$price = $deal->getPrice();
							$specialPrice = $deal->getSpecialPrice();
							$amount+= ($price-$specialPrice)*$dealQty;
						}
					}
				}
			}
			return $amount;
		}
	}
	
	public function collect(Mage_Sales_Model_Quote_Address $address)
    {
		if(Mage::getStoreConfig('deals/general/enabled')){
			$amount = $this->getAmount();
			if($amount>0){
				parent::collect($address);
				if (($address->getAddressType() == 'billing')) {
					return $this;
				}

				

				if ($amount) {
					$this->_addAmount(-$amount);
					$this->_addBaseAmount(-$amount);
				}

				return $this;
			}
		}
    }

    /**
     * Add giftcard totals information to address object
     *
     * @param   Mage_Sales_Model_Quote_Address $address
     */
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
		if(Mage::getStoreConfig('deals/general/enabled')){
			$amount = $this->getAmount();
			if($amount>0){
				if (($address->getAddressType() == 'billing')) {
					if ($amount != 0) {
						$address->addTotal(array(
							'code'  => $this->getCode(),
							'title' => $this->getLabel(),
							'value' => -$amount
						));
					}
				}

				return $this;
			}
		}
    }
}