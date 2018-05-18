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

class MGS_Oscheckout_Block_Step_Billing extends Mage_Checkout_Block_Onepage_Billing {

    protected $_address;

    /**
     * Initialize billing address step
     *
     */
    protected function _construct() {
        $this->getCheckout()->setStepData('billing', array(
            'label' => Mage::helper('checkout')->__('Billing Information'),
            'is_show' => $this->isShow()
        ));

        if ($this->isCustomerLoggedIn()) {
            $this->getCheckout()->setStepData('billing', 'allow', true);
        }
        $this->settings = Mage::helper('oscheckout/checkout')->loadSettings();
        parent::_construct();
    }
    
	public function getCountryHtmlSelect($type)
    {
        if(isset($this->settings['default_country_id']))
    	{
			$countryId = $this->settings['default_country_id'];
    	}
        if (is_null($countryId)) {
            $countryId = Mage::helper('core')->getDefaultCountry();
        }
        $select = $this->getLayout()->createBlock('core/html_select')
            ->setName($type.'[country_id]')
            ->setId($type.':country_id')
            ->setTitle(Mage::helper('checkout')->__('Country'))
            ->setClass('form-control validate-select')
            ->setValue($countryId)
            ->setOptions($this->getCountryOptions());
        if ($type === 'shipping') {
            $select->setExtraParams('onchange="shipping.setSameAsBilling(false);"');
        }

        return $select->getHtml();
    }
    public function isUseBillingAddressForShipping() {
        if (($this->getQuote()->getIsVirtual())
                || !$this->getQuote()->getShippingAddress()->getSameAsBilling()) {
            return true;
        }
        return true;
    }
    public function BillingAddressForShipping() {
        if (($this->getQuote()->getIsVirtual()))
                {
            return false;
        }
        return true;
    }
    /**
     * Return country collection
     *
     * @return Mage_Directory_Model_Mysql4_Country_Collection
     */
    public function getCountries() {
        return Mage::getResourceModel('directory/country_collection')->loadByStore();
    }

    /**
     * Return checkout method
     *
     * @return string
     */
    public function getMethod() {
        return $this->getQuote()->getCheckoutMethod();
    }

    /**
     * Return Sales Quote Address model
     *
     * @return Mage_Sales_Model_Quote_Address
     */
    public function getAddress() {
        if (is_null($this->_address)) {
            if ($this->isCustomerLoggedIn()) {
                $this->_address = $this->getQuote()->getBillingAddress();
            } else {
                $this->_address = Mage::getModel('sales/quote_address');
            }
        }

        return $this->_address;
    }

    /**
     * Return Customer Address First Name
     * If Sales Quote Address First Name is not defined - return Customer First Name
     *
     * @return string
     */
    public function getFirstname() {
        $firstname = $this->getAddress()->getFirstname();
        if (empty($firstname) && $this->getQuote()->getCustomer()) {
            return $this->getQuote()->getCustomer()->getFirstname();
        }
        return $firstname;
    }

    /**
     * Return Customer Address Last Name
     * If Sales Quote Address Last Name is not defined - return Customer Last Name
     *
     * @return string
     */
    public function getLastname() {
        $lastname = $this->getAddress()->getLastname();
        if (empty($lastname) && $this->getQuote()->getCustomer()) {
            return $this->getQuote()->getCustomer()->getLastname();
        }
        return $lastname;
    }

    /**
     * Check is Quote items can ship to
     *
     * @return boolean
     */
    public function canShip() {
        return!$this->getQuote()->isVirtual();
    }

    public function getSaveUrl() {
        
    }
   
      public function AjaxSaveBillingFields($name)
    {
        $fields = array('country', 'zip code / postal code', 'state/region', 'city');

        if(in_array($name, $fields))
        {
            return true;
        }

        return false;
    }
    

    

}