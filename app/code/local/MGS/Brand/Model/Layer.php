<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

class MGS_Brand_Model_Layer extends Mage_Catalog_Model_Layer {

    public function getProductCollection() {
        if (isset($this->_productCollections[$this->getCurrentCategory()->getId()])) {
            $collection = $this->_productCollections[$this->getCurrentCategory()->getId()];
        } else {
            $ids = array();
            $params = Mage::app()->getRequest()->getParams();
            $products = Mage::getModel('brand/product')->getCollection()
                    ->addFieldToFilter('brand_id', array('eq' => $params['id']));
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
            $this->prepareProductCollection($collection);
            $this->_productCollections[$this->getCurrentCategory()->getId()] = $collection;
        }
        return $collection;
    }

}
