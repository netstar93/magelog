<?php
class MGS_Storelocator_Block_List extends Mage_Core_Block_Template
{
    protected $_storesCollection = null;
    
    /**
     * Retrieve stores collection
     *
     * @return MGS_Storelocator_Model_Resource_Storelocator_Collection
     */
    protected function _getStoreCollection()
    {
        $collection = Mage::getModel('mgs_storelocator/storelocator')->getCollection()
            ->addStoreFilter(Mage::app()->getStore()->getId())
            ->addStatusFilter();
        return $collection;
    }
    
    /**
     * Retrieve prepared stores collection
     *
     * @return MGS_Storelocator_Model_Resource_Storelocator_Collection
     */
    public function getStoreCollection()
    {
        //search by country state city
        $data = $this->getRequest()->getQuery();
        $country = '';
        $state = '';
        $city = '';
        $zipcode = '';
        if(!empty($data)){
            if(isset($data['country'])){
                $country = $data['country'];
            }
            
            if(isset($data['state'])){
                $state = $data['state'];
            }
            
            if(isset($data['city'])){
                $city = $data['city'];
            }
			
			if(isset($data['zipcode'])){
                $zipcode = $data['zipcode'];
            }
        }
		
        if (is_null($this->_storesCollection)) {
            $this->_storesCollection = $this->_getStoreCollection();
            $this->_storesCollection->prepareForList($this->getCurrentPage());
            //search by country
            if(!empty($country)){
                $this->_storesCollection->addFieldToFilter('country', array('like'=>$country));
            }
            
            //search by state
            if(!empty($state)){
                $this->_storesCollection->addFieldToFilter('state', array('like'=>$state));
            }
            
            //search by city
            if(!empty($city)){
                $this->_storesCollection->addFieldToFilter('city', array('like'=>$city));
            }
			
			//search by zipcode
            if(!empty($zipcode)){
                $this->_storesCollection->addFieldToFilter('zipcode', array('like'=>$zipcode));
            }
        }
		
		//print_r($data);
		if(!empty($data['lat_search']) && !empty($data['long_search'])){
			
            $this->_storesCollection->getSelect()->where('3959 * acos(cos( radians('.$data['lat_search'].')) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$data['long_search'].')) + sin( radians('.$data['lat_search'].') ) * sin( radians( latitude ) ) ) < '.$data['radius']);
        }
		
        //echo $this->_storesCollection->getSelect();
        return $this->_storesCollection;
    }
    
    /**
     * Return URL to stores's view page
     *
     * @param  MGS_Storelocator_Model_Storelocator $storelocator
     * @return string
     */
    public function getStoreUrl($storelocator)
    {
        return $this->getUrl('storelocator/').$storelocator->getIdentifier();
    }
    
    /**
     * Fetch the current page for the stores list
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->getData('current_page') ? $this->getData('current_page') : 1;
    }
    
    /**
     * Get a pager
     *
     * @return string|null
     */
    public function getPager()
    {
       $pager = $this->getChild('stores.list.pager');
        if ($pager) {
            $storesPerPage = 10;
            $pager->setAvailableLimit(array($storesPerPage => $storesPerPage));
            $pager->setTotalNum($this->getStoreCollection()->getSize());
           // $pager->setPagerUrl(array('country'=>'india'));
            $pager->setCollection($this->getStoreCollection());
            $pager->setShowPerPage(true);
            $pager->setShowAmounts(true);
            return $pager->toHtml();
        }

        return null;
    }
    
    /**
     * Retrieve form posting url
     *
     * @return string
     */
    public function getPostActionUrl()
    {
        return $this->helper('mgs_storelocator')->getSearchPostUrl();
    }
    
    /**
     * Retrieve form data
     *
     * @return Varien_Object
     */
    public function getFormData()
    {
        $data = $this->getData('form_data');
        if (is_null($data)) {
            //session form data
            $formData = Mage::getSingleton('core/session')->getSearchFormData(true);
            $data = new Varien_Object();
            if ($formData) {
                $data->addData($formData);
            }else {
                //post form data
                $formPostData = $this->getRequest()->getQuery();
                if($formPostData){
                    $data->addData($formPostData);
                }
            }
            $this->setData('form_data', $data);
        }
        return $data;
    }
    
    /**
     * Return country collection
     *
     * @return Mage_Directory_Model_Mysql4_Country_Collection
     */
    public function getCountries()
    {
        $_countries = Mage::getModel('directory/country')->getCollection()
        ->loadData()
        ->toOptionArray(false);
       // ZEND_DEBUG::dump($_countries);
        return $_countries;
    }
    
    /**
     * get country name by county code
     * @param string $countryCode country code
     * @return string
     */
    public function getCountryName($countryCode)
    {
        return $this->helper('mgs_storelocator')->getCountryNameByCode($countryCode);
    }
    
    /**
     * Return store zoom level/default zoom level
     *
     * @param MGS_Storelocator_Model_Storelocator $storelocator
     * @return int
     */
    public function getZoomLevel($storelocator=NULL)
    {
        //Return store zoom level
        if(!is_null($storelocator)) {
            if($storelocator->getZoomLevel()){
                return $storelocator->getZoomLevel();
            } else {
                // Return default config zoom level
                return 14;
            }
        } else {
            // Return default config zoom level
            return 14;
        }
        return;
    }
    
     /**
     * Return store radius/default radius
     *
     * @param MGS_Storelocator_Model_Storelocator $storelocator
     * @return int
     */
    public function getRadius($storelocator=NULL)
    {
        //Return store radius
        if(!is_null($storelocator)) {
            if($storelocator->getRadius()){
                return $storelocator->getRadius();
            } else {
                // Return default config radius
                return 100;
            }
        } else {
            // Return default config radius
            return 100;
        }
        return;
    }
}