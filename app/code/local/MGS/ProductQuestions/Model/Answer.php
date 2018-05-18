<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Model_Answer extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('productquestions/answer');
    }

}
