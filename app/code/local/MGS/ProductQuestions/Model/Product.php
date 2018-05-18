<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Model_Product {

    public function toOptionArray() {
        $collection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('status', array('eq' => 1))
                ->addAttributeToFilter('visibility', array('neq' => 1))
                ->addAttributeToSort('name', 'ASC');
        $products = array();
        $products[] = array(
            'value' => '',
            'label' => '-- Select --'
        );
        foreach ($collection as $product) {
            $products[] = array(
                'value' => $product->getId(),
                'label' => $product->getName()
            );
        }
        return $products;
    }

}
