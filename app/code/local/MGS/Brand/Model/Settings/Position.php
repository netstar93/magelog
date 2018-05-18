<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

class MGS_Brand_Model_Settings_Position {

    public function toOptionArray() {
        return array(
            array('value' => 'no', 'label' => Mage::helper('adminhtml')->__('No')),
            array('value' => 'top-left', 'label' => Mage::helper('adminhtml')->__('Top Left')),
            array('value' => 'bottom-left', 'label' => Mage::helper('adminhtml')->__('Bottom Left')),
            array('value' => 'top-right', 'label' => Mage::helper('adminhtml')->__('Top Right')),
            array('value' => 'bottom-right', 'label' => Mage::helper('adminhtml')->__('Bottom Right')),
            array('value' => 'top-content', 'label' => Mage::helper('adminhtml')->__('Top Content')),
            array('value' => 'bottom-content', 'label' => Mage::helper('adminhtml')->__('Bottom Content'))
        );
    }

}
