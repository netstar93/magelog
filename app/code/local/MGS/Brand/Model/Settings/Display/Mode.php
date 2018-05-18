<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

class MGS_Brand_Model_Settings_Display_Mode {

    public function toOptionArray() {
        return array(
            array('value' => 0, 'label' => Mage::helper('adminhtml')->__('No')),
            array('value' => 1, 'label' => Mage::helper('adminhtml')->__('Show Brand Name')),
            array('value' => 2, 'label' => Mage::helper('adminhtml')->__('Show Brand Logo')),
            array('value' => 3, 'label' => Mage::helper('adminhtml')->__('Show Brand Name And Logo'))
        );
    }

}
