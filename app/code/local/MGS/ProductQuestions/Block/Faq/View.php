<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Block_Faq_View extends Mage_Core_Block_Template {

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function getTopic() {
        $topicId = $this->getRequest()->getParam('topic');
        $topic = Mage::getModel('productquestions/topic')->load($topicId);
        return $topic;
    }

    public function getQuestions() {
        $topic = $this->getTopic();
        $topicId = $topic->getId();
        $isLoggedIn = Mage::getSingleton('customer/session')->isLoggedIn();
        $order = Mage::helper('productquestions')->sortBy();
        if ($order == 'latest') {
            $sort = 'created_at';
        } elseif ($order == 'score') {
            $sort = 'score';
        } else {
            $sort = 'id';
        }
        if ($isLoggedIn) {
            $collection = Mage::getModel('productquestions/question')->getCollection()
                    ->addFieldToFilter('topic_id', $topicId)
                    ->addFieldToFilter('status', 2)
                    ->setOrder($sort, 'DESC');
        } else {
            $collection = Mage::getModel('productquestions/question')->getCollection()
                    ->addFieldToFilter('topic_id', $topicId)
                    ->addFieldToFilter('status', 2)
                    ->addFieldToFilter('visibility', 1)
                    ->setOrder($sort, 'DESC');
        }
        return $collection;
    }

    public function getAnswersByQuestionId($questionId) {
        $collection = Mage::getModel('productquestions/answer')->getCollection()
                ->addFieldToFilter('question_id', $questionId)
                ->addFieldToFilter('a_status', 2)
                ->setOrder('a_content', 'ASC');
        return $collection;
    }

}
