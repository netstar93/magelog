<?php

class MGS_Oscheckout_Helper_Checkout extends Mage_Core_Helper_Abstract {

    function __construct()
    {
        $this->settings = $this->loadSettings();
    }

    public function loadSettings()
    {
        $settings = array();
        $items = array();
        $items = Mage::getStoreConfig('oscheckout');
        foreach ($items as $config) {
            foreach ($config as $key => $value) {
                $settings[$key] = $value;
            }
        }
         if(empty($settings['default_country_id']))
        {
            $settings['default_country_id'] = Mage::getStoreConfig('general/country/default');
        }
        return $settings;
    }
    
	
    

    public function load_exclude_data(&$data) {
        if (Mage::getStoreConfig('oscheckout/display/city')){
            $data['city'] = '-';
        }
		if (Mage::getStoreConfig('oscheckout/display/country')){
			$data['country_id'] = $this->settings['default_country_id'];
        }
        if (Mage::getStoreConfig('oscheckout/display/telephone')){
            $data['telephone'] = '-';
        }
		if (Mage::getStoreConfig('oscheckout/display/state')){
            $data['region'] = '-';
            $data['region_id'] = '1';
        }
		if (Mage::getStoreConfig('oscheckout/display/zipcode')){
            $data['postcode'] = '-';
        }
		if (Mage::getStoreConfig('oscheckout/display/company')){
            $data['company'] = '';
        }
		if (Mage::getStoreConfig('oscheckout/display/fax')){
            $data['fax'] = '';
        }
		if (Mage::getStoreConfig('oscheckout/display/address')){
            $data['street'][] = '-';
        }
        return $data;
    }
    
    //check the exclude fields and assign - to that values when ajax updates trigger
    
	public function load_add_data($data) 
    {        
        if (isset($data['city']))
        {
			 $data['city'] = '';
        }
        if (empty($data['country_id']))
        {
			 $data['country_id'] = $this->settings['default_country_id']; 
        }
        if (empty($data['telephone']))
        {
            $data['telephone'] = '-';
        }
        if (empty($data['region_id']))
        {
            $data['region_id'] = '-';
            $data['region'] = '-';
        }
        if (empty($data['postcode']))
        {
            $data['postcode'] = '-';
        }
        if (empty($data['company']))
        {
            $data['company'] = '-';
        }
        if (empty($data['fax']))
        {
            $data['fax'] = '-';
        }         
        if (empty($data['street'][0]))
        {
            $data['street'][0] = '-';
        }
        return $data;
    }
	

}