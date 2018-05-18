<?php
/**
 * @name         :  MGS One Step Checkout
 * @version      :  1.4
 * @since        :  Magento ver 1.4, 1.5, 1.6, 1.7
 * @author       :  MGS - http://www.mage-shop.com
 * @copyright    :  Copyright (C) 2011 Powered by MGS
 * @license      :  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Creation Date:  Sept 06 2012
 * 
 * */
class MGS_Oscheckout_Block_Checkout_Links extends Mage_Checkout_Block_Links
{
   
    public function addCheckoutLink()
    {

        if (!Mage::getStoreConfig('oscheckout/general/enabled')){
            return parent::addCheckoutLink();
        }

        if (!$this->helper('checkout')->canOnepageCheckout()) {
            return $this;
        }
        if ($parentBlock = $this->getParentBlock()) {
            $text = $this->__('Checkout');
			if(Mage::getStoreConfig('oscheckout/general/checkout_link')!=''){
				$text = Mage::getStoreConfig('oscheckout/general/checkout_link');
			}
            $parentBlock->addLink($text, 'oscheckout', $text, true, array('_secure'=>true), 60, null, 'class="top-link"');
        }
        return $this;
    }
}
