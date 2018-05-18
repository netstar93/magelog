<?php

class MGS_Mpanel_Model_Source_Attribute {

    public function toOptionArray() {
        $attrs = array(array('value' => '', 'label' => ''));
        $productAttrs = Mage::getResourceModel('catalog/product_attribute_collection');
        foreach ($productAttrs as $productAttr) {
            $attrs[] = array('value' => $productAttr->getData('attribute_code'), 'label' => $productAttr->getData('frontend_label'));
        }
        return $attrs;
    }

}
