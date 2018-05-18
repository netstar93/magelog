<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Model_System_Config_Source_Sortby {

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray() {
        return array(
            array('value' => 'latest', 'label' => Mage::helper('productquestions')->__('Latest')),
            array('value' => 'score', 'label' => Mage::helper('productquestions')->__('Score')),
            array('value' => 'order', 'label' => Mage::helper('productquestions')->__('Order')),
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray() {
        return array(
            'latest' => Mage::helper('productquestions')->__('Latest'),
            'score' => Mage::helper('productquestions')->__('Score'),
            'order' => Mage::helper('productquestions')->__('Order'),
        );
    }

}
