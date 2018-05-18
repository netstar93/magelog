<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

class MGS_Brand_Block_Brand extends Mage_Core_Block_Template {

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function getBrandCollection() {
        $params = Mage::app()->getRequest()->getParams();
        $collection = Mage::getModel('brand/brand')->getCollection()
                ->addFieldToFilter('status', array('eq' => 1));
        $collection->addStoreFilter(Mage::app()->getStore()->getStoreId());
        if (isset($params['q']) && $params['q'] != '') {
            $collection->addFieldToFilter('title', array('like' => '%' . $params['q'] . '%'));
        }
        if (isset($params['char']) && $params['char'] != '') {
            $collection->addFieldToFilter('title', array('like' => $params['char'] . '%'));
        }
        if (isset($params['dir']) && $params['order'] != '') {
            $collection->setOrder($params['order'], $params['dir']);
        } else {
            $collection->setOrder('priority', 'asc');
        }
        return $collection;
    }

    public function getFeaturedBrandCollection() {
        $collection = Mage::getModel('brand/brand')->getCollection()
                ->addFieldToFilter('status', array('eq' => 1))
                ->addFieldToFilter('is_featured', array('eq' => 1))
                ->setOrder('priority', 'asc');
        $collection->addStoreFilter(Mage::app()->getStore()->getStoreId());
        return $collection;
    }

    public function getProductCount($brandId) {
        $ids = array();
        $products = Mage::getModel('brand/product')->getCollection()
                ->addFieldToFilter('brand_id', array('eq' => $brandId));
        foreach ($products as $product) {
            $ids[] = (int) $product->getProductId();
        }
        $collection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('*');
        if (count($ids)) {
            $collection->addFieldToFilter('entity_id', array('in' => $ids));
        } else {
            $collection->addFieldToFilter('entity_id', array('eq' => 0));
        }
        $collection
                ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                ->addMinimalPrice()
                ->addFinalPrice()
                ->addTaxPercents();
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
        return count($collection);
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

    public function getTitle() {
        $helper = Mage::helper('brand');
        if ($helper->title() != '') {
            $title = Mage::helper('brand')->title();
        } else {
            $title = 'Brand';
        }
        return $title;
    }

    public function getDescription() {
        $helper = Mage::helper('brand');
        $description = $helper->description();
        return $description;
    }

}
