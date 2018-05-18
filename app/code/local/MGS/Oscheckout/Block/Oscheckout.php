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

class MGS_Oscheckout_Block_Oscheckout extends Mage_Checkout_Block_Onepage_Abstract {
//get default country and set estimate rates
    public function _construct() {
        parent::_construct();

        $rates = $this->getEstimateRates();

        $defaut_country = Mage::getStoreConfig('oscheckout/general/default_country_id');
        if (!$defaut_country) {
            $defaut_country = Mage::getStoreConfig('general/country/default');
        }

        $this->getQuote()->getShippingAddress()->setCountryId($defaut_country)->setCollectShippingRates(true)->save();
    	
	}
//get all shipping rates 
    public function getEstimateRates() {
        if (empty($this->_rates)) {
            $groups = $this->getQuote()->getShippingAddress()->getGroupedAllShippingRates();
            $this->_rates = $groups;
        }
        return $this->_rates;
    }

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }
    //get shipping methods 
	public function shippingmethods($shipping,$methods)
	{
	if(($shipping)&&($methods))
	{
		return true;	
	}
	
	}
//getting steps based on the product
    public function getSteps() {
        $steps = array();

       //steps for virtual product
        if ($this->getOnepage()->getQuote()->isVirtual())
        {
            $stepCodes = array('billing', 'payment', 'review');
        }
        //steps for other product
        else
        {
            $stepCodes = array('billing', 'shipping', 'shipping_method', 'payment', 'review');
        }

        foreach ($stepCodes as $step)
        {

            $steps[$step] = $this->getCheckout()->getStepData($step);
        }
      
        return $steps;
    }

//check the active step
    public function getActiveStep()
    {
        return $this->isCustomerLoggedIn() ? 'billing' : 'login';
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