<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Model_System_Config_Source_Customer_Type {

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray() {
        return array(
            array('value' => 0, 'label' => Mage::helper('productquestions')->__('Registered Customer')),
            array('value' => 1, 'label' => Mage::helper('productquestions')->__('Anyone Customer')),
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray() {
        return array(
            0 => Mage::helper('productquestions')->__('Registered Customer'),
            1 => Mage::helper('productquestions')->__('Anyone Customer'),
        );
    }

}
