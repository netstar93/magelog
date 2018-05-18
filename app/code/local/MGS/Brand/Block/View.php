<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

class MGS_Brand_Block_View extends Mage_Catalog_Block_Product_List {

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function getBrand() {
        $params = $this->getRequest()->getParams();
        $model = Mage::getModel('brand/brand')->load($params['id']);
        return $model;
    }

}
