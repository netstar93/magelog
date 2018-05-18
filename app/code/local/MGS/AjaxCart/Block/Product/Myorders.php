<?php

/* * ****************************************************
 * Package   : AjaxCart
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_AjaxCart_Block_Product_Myorders extends Mage_Catalog_Block_Product {

    protected function _construct() {
        parent::_construct();
        $this->setTemplate('mgs/ajaxcart/product/myorders.phtml');
    }

    protected function _toHtml() {
        return parent::_toHtml();
    }

}
