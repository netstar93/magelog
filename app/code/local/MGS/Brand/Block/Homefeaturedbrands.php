<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

class MGS_Brand_Block_Homefeaturedbrands extends Mage_Core_Block_Template {

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function getCollection() {
        $collection = Mage::getModel('brand/brand')->getCollection()
                ->addFieldToFilter('status', array('eq' => 1))
                ->addFieldToFilter('is_featured', array('eq' => 1))
                ->setOrder('priority', 'asc');
        $collection->addStoreFilter(Mage::app()->getStore()->getStoreId());
        if ($this->getBrandNumber()) {
            $collection->getSelect()->limit($this->getBrandNumber());
        }
        return $collection;
    }

    public function getUrlKey() {
        $helper = Mage::helper('brand');
        if ($helper->urlKey() != '') {
            $urlKey = Mage::helper('brand')->urlKey();
        } else {
            $urlKey = 'brand';
        }
        return $urlKey;
    }

}
