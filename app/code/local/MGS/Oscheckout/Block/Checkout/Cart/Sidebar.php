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
class MGS_Oscheckout_Block_Checkout_Cart_Sidebar extends Mage_Checkout_Block_Cart_Sidebar
{
   
    public function getCheckoutUrl()
    {
        if(!Mage::getStoreConfig('oscheckout/general/enabled')){
            return parent::getCheckoutUrl();
        }
        return $this->getUrl('oscheckout', array('_secure'=>true));
    }
}
