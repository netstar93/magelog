<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

class MGS_Brand_Block_Product_Brand extends Mage_Catalog_Block_Product_Abstract {

    var $_columnsCount = 4;

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function getBrand($productId) {
        $model = Mage::getModel('brand/product')->getCollection()->addFieldToFilter('product_id', array('eq' => $productId))->getFirstItem();
        $brandId = $model->getData('brand_id');
        return Mage::getModel('brand/brand')->load($brandId);
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

    public function getRelatedProducts($brandId, $pageSize = 8, $curPage = 1, $sortBy = 'random', $sortDir = 'asc', $productId) {
        $ids = array();
        $products = Mage::getModel('brand/product')->getCollection()
                ->addFieldToFilter('brand_id', array('eq' => $brandId))
				->addFieldToFilter('product_id', array('neq' => $productId));
        foreach ($products as $product) {
            $ids[] = (int) $product->getProductId();
        }
        if (count($ids)) {
            $collection = Mage::getModel('catalog/product')->getCollection()
                    ->addFieldToFilter('entity_id', array('in' => $ids))
                    ->addAttributeToSelect('*');
        } else {
            $collection = Mage::getModel('catalog/product')->getCollection()
                    ->addFieldToFilter('entity_id', array('eq' => 0))
                    ->addAttributeToSelect('*');
        }
        $collection
                ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                ->addStoreFilter()
                ->addPriceData()
                ->addTaxPercents()
                ->addUrlRewrite();
        if ($sortBy == null || $sortBy == "random") {
            $collection
                    ->setOrder($sortBy, $sortDir)
                    ->getSelect()->order(new Zend_Db_Expr('RAND()'));
        } else {
            $collection
                    ->setOrder($sortBy, $sortDir);
        }
        $collection
                ->setPageSize($pageSize)
                ->setCurPage($curPage);

        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);

        return $collection;
    }

}
