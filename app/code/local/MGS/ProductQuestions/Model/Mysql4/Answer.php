<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Model_Mysql4_Answer extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {
        // Note that the id refers to the key field in your database table.
        $this->_init('productquestions/answer', 'id');
    }

}
