<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Model_Question extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('productquestions/question');
    }

    public function haveAnswers() {
        $answers = Mage::getModel('productquestions/answer')->getCollection()
                ->addFieldToFilter('question_id', $this->getId())
                ->addFieldToFilter('a_status', 2)
                ->setOrder('a_content', 'ASC');
        if (count($answers)) {
            return true;
        } else {
            return false;
        }
    }

}
