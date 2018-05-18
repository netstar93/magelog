<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

class MGS_Brand_Block_Search extends Mage_Core_Block_Template {

    public function _prepareLayout() {
        return parent::_prepareLayout();
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
