<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

class MGS_Brand_Model_System_Config_Source_Sortby {

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray() {
        return array(
            array('value' => 'random', 'label' => Mage::helper('adminhtml')->__('Random')),
            array('value' => 'position', 'label' => Mage::helper('adminhtml')->__('Position')),
            array('value' => 'name', 'label' => Mage::helper('adminhtml')->__('Name')),
            array('value' => 'price', 'label' => Mage::helper('adminhtml')->__('Price')),
            array('value' => 'created_at', 'label' => Mage::helper('adminhtml')->__('Created At'))
        );
    }

}
