<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

class MGS_Brand_Model_Settings_Template {

    public function toOptionArray() {
        return array(
            array('value' => '1column', 'label' => Mage::helper('adminhtml')->__('1 Column')),
            array('value' => '2columns-left', 'label' => Mage::helper('adminhtml')->__('2 Columns Left')),
            array('value' => '2columns-right', 'label' => Mage::helper('adminhtml')->__('2 Columns Right')),
            array('value' => '3columns', 'label' => Mage::helper('adminhtml')->__('3 Columns'))
        );
    }

}
