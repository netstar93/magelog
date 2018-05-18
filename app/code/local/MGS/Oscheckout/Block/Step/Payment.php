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
 * */?>
<?php


class MGS_Oscheckout_Block_Step_Payment extends Mage_Checkout_Block_Onepage_Payment {

    protected function _construct()
    {
        $this->getCheckout()->setStepData('payment', array(
            'label' => $this->__('Payment Information'),
            'is_show' => $this->isShow()
        ));
        parent::_construct();
    }

    /**
     * Getter
     *
     * @return float
     */
    public function getQuoteBaseGrandTotal()
    {
        return (float) $this->getQuote()->getBaseGrandTotal();
    }
     public function getOnepage()
    {
        return Mage::getSingleton('checkout/type_onepage');
    }

    //get product is virtual product  or not
    public function getVirtual()
    {
        if ($this->getOnepage()->getQuote()->isVirtual())
        {
            return true;
        } 
        else
        {
            return false;
        }
    }

}
