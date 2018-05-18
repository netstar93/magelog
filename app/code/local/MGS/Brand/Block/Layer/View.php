<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

class MGS_Brand_Block_Layer_View extends Mage_Catalog_Block_Layer_View {

    protected function _construct() {
        parent::_construct();
        Mage::register('current_layer', $this->getLayer(), true);
    }

    protected function _initBlocks() {
        $this->_stateBlockName = 'brand/layer_state';
        $this->_categoryBlockName = 'brand/layer_filter_category';
        $this->_attributeFilterBlockName = 'brand/layer_filter_attribute';
        $this->_priceFilterBlockName = 'brand/layer_filter_price';
        $this->_decimalFilterBlockName = 'brand/layer_filter_decimal';
    }

    protected function _getAttributeFilterBlockName() {
        return 'brand/layer_filter_attribute';
    }

    public function getLayer() {
        return Mage::getSingleton('brand/layer');
    }

}
