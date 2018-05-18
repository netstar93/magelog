<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Block_Question extends Mage_Core_Block_Template {

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function getQuestions() {
        $isLoggedIn = Mage::getSingleton('customer/session')->isLoggedIn();
        $productId = Mage::registry('current_product')->getId();
		$storeId = Mage::app()->getStore()->getId();
        $questionIds = array();
        $questionCollection = Mage::getModel('productquestions/question')->getCollection()
                ->addFieldToFilter('product_id', array('eq' => $productId))
                ->addFieldToFilter('store_id', array('in' => array(0, $storeId)))
                ->addFieldToFilter('status', array('eq' => 2));
        if (count($questionCollection)) {
            foreach ($questionCollection as $question) {
                $questionIds[] = $question->getId();
            }
        }
        $sharingCollection = Mage::getModel('productquestions/sharing')->getCollection()
                ->addFieldToFilter('product_id', array('eq' => $productId));
        if (count($sharingCollection)) {
            foreach ($sharingCollection as $sharing) {
                $questionIds[] = $sharing->getQuestionId();
            }
        }
        if ($isLoggedIn) {
            $collection = Mage::getModel('productquestions/question')->getCollection()
                    ->addFieldToFilter('id', array('in' => $questionIds));
            $collection->setOrder('content', 'ASC');
        } else {
            $collection = Mage::getModel('productquestions/question')->getCollection()
                    ->addFieldToFilter('id', array('in' => $questionIds))
                    ->addFieldToFilter('visibility', array('eq' => 1));
            $collection->setOrder('content', 'ASC');
        }
        return $collection;
    }

    public function getAnswers($questionId) {
        $collection = Mage::getModel('productquestions/answer')->getCollection()
                ->addFieldToFilter('question_id', array('eq' => $questionId))
                ->addFieldToFilter('a_status', array('eq' => 2));
        return $collection;
    }

    public function getProductId() {
        $productId = Mage::registry('current_product')->getId();
        return $productId;
    }

}
