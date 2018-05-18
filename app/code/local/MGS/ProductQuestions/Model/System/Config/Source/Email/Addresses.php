<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Model_System_Config_Source_Email_Addresses {

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray() {
        return array(
            array('value' => 'general', 'label' => Mage::helper('productquestions')->__('General Contact')),
            array('value' => 'sales', 'label' => Mage::helper('productquestions')->__('Sales Representative')),
            array('value' => 'support', 'label' => Mage::helper('productquestions')->__('Customer Support')),
            array('value' => 'custom1', 'label' => Mage::helper('productquestions')->__('Custom Email 1')),
            array('value' => 'custom2', 'label' => Mage::helper('productquestions')->__('Custom Email 2')),
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray() {
        return array(
            'general' => Mage::helper('productquestions')->__('General Contact'),
            'sales' => Mage::helper('productquestions')->__('Sales Representative'),
            'support' => Mage::helper('productquestions')->__('Customer Support'),
            'custom1' => Mage::helper('productquestions')->__('Custom Email 1'),
            'custom2' => Mage::helper('productquestions')->__('Custom Email 2'),
        );
    }

}
