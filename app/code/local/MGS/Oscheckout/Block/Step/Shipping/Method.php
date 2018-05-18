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

class MGS_Oscheckout_Block_Step_Shipping_Method extends Mage_Checkout_Block_Onepage_Shipping_Method
{
    protected function _construct()
    {
        $this->getCheckout()->setStepData('shipping_method', array(
            'label'     => Mage::helper('checkout')->__('Shipping Method'),
            'is_show'   => $this->isShow()
        ));
        parent::_construct();
    }

    /**
     * Retrieve is allow and show block
     *
     * @return bool
     */
    public function isShow()
    {
        return !$this->getQuote()->isVirtual();
    }
}
