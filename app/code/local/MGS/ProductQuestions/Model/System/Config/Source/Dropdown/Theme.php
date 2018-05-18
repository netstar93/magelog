<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Model_System_Config_Source_Dropdown_Theme {

    public function toOptionArray() {
        return array(
            array('value' => 'red', 'label' => Mage::helper('productquestions')->__('Red (default)')),
            array('value' => 'white', 'label' => Mage::helper('productquestions')->__('White')),
            array('value' => 'blackglass', 'label' => Mage::helper('productquestions')->__('Blackglass')),
            array('value' => 'clean', 'label' => Mage::helper('productquestions')->__('Clean')),
        );
    }

}
