<?php

/* * ****************************************************
 * Package   : AjaxCart
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_AjaxCart_Block_Product_Deletion extends Mage_Catalog_Block_Product {

    private $product;

    protected function _construct() {
        parent::_construct();
        $this->setTemplate('mgs/ajaxcart/product/deletion.phtml');
    }

    protected function _toHtml() {
        return parent::_toHtml();
    }

    public function setProduct($product) {
        $this->product = $product;
        return $this;
    }

    public function getProduct() {
        return $this->product;
    }

}
