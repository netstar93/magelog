<?php
class MGS_Storelocator_Block_View extends Mage_Core_Block_Template
{ 
    /**
     * Current news item instance
     *
     * @var MGS_Storelocator_Model_Storelocator
     */
    protected $_store;

    /**
     * Return parameters for back url
     *
     * @param array $additionalParams
     * @return array
     */
    protected function _getBackUrlQueryParams($additionalParams = array())
    {
        return array_merge(array('p' => $this->getPage()), $additionalParams);
    }

    /**
     * Return URL to the store view page
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/', array('_query' => $this->_getBackUrlQueryParams()));
    }

    /**
     * Return URL for resized News Item image
     *
     * @param MGS_Storelocator_Model_Storelocator $store
     * @param integer $width
     * @return string|false
     */
    public function getImageUrl($store, $width)
    {
        return Mage::helper('mgs_storelocator/image')->resize($store, $width);
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
    
    /**
     * get country name by county code
     * @param string $countryCode country code
     * @return string
     */
    public function getCountryName($countryCode)
    {
        return $this->helper('mgs_storelocator')->getCountryNameByCode($countryCode);
    }
}