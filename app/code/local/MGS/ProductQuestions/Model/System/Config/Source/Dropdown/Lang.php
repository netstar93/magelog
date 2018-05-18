<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Model_System_Config_Source_Dropdown_Lang {

    public function toOptionArray() {
        return array(
            array('value' => 'en', 'label' => Mage::helper('productquestions')->__('English (default)')),
            array('value' => 'nl', 'label' => Mage::helper('productquestions')->__('Dutch')),
            array('value' => 'fr', 'label' => Mage::helper('productquestions')->__('French')),
            array('value' => 'de', 'label' => Mage::helper('productquestions')->__('German')),
            array('value' => 'pt', 'label' => Mage::helper('productquestions')->__('Portuguese')),
            array('value' => 'ru', 'label' => Mage::helper('productquestions')->__('Russian')),
            array('value' => 'es', 'label' => Mage::helper('productquestions')->__('Spanish')),
            array('value' => 'tr', 'label' => Mage::helper('productquestions')->__('Turkish')),
        );
    }

}
