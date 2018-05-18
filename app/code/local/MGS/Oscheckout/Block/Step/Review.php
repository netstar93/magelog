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
 * */ ?>
<?php

class MGS_Oscheckout_Block_Step_Review extends Mage_Checkout_Block_Onepage_Abstract {

    protected function _construct() {
        $this->getCheckout()->setStepData('review', array(
            'label' => Mage::helper('checkout')->__('Order Review'),
            'is_show' => $this->isShow()
        ));
        parent::_construct();

        $this->getQuote()->collectTotals()->save();
    }

    public function getItems() {
        return $this->getQuote()->getAllVisibleItems();
    }

    public function getTotals() {
        return $this->getQuote()->getTotals();
    }

}
