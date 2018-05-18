<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

class MGS_Brand_Model_System_Config_Source_Sortdir {

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray() {
        return array(
            array('value' => 'asc', 'label' => Mage::helper('adminhtml')->__('Ascending')),
            array('value' => 'desc', 'label' => Mage::helper('adminhtml')->__('Descending'))
        );
    }

}
