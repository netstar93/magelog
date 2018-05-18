<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Model_Status extends Varien_Object {

    const STATUS_PENDING = 1;
    const STATUS_APPROVED = 2;
    const STATUS_DECLINED = 3;

    static public function getOptionArray() {
        return array(
            self::STATUS_PENDING => Mage::helper('productquestions')->__('Pending'),
            self::STATUS_APPROVED => Mage::helper('productquestions')->__('Approved'),
            self::STATUS_DECLINED => Mage::helper('productquestions')->__('Declined')
        );
    }

}
