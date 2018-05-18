<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Block_Faq_Topic extends Mage_Core_Block_Template {

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function getParentTopics() {
		$storeId = Mage::app()->getStore()->getId();
        $collection = Mage::getModel('productquestions/topic')->getCollection()
                ->addFieldToFilter('parent_id', 0)
                //->addFieldToFilter('show_on_block', 1)
                ->addFieldToFilter('status', 1)
				->addFieldToFilter('store_id', array('in' => array(0, $storeId)))
                ->setOrder('title', 'ASC');        
        return $collection;
    }

    public function getChildTopicsByParentId($parentId) {
		$storeId = Mage::app()->getStore()->getId();
        $collection = Mage::getModel('productquestions/topic')->getCollection()
                ->addFieldToFilter('parent_id', $parentId)
                //->addFieldToFilter('show_on_block', 1)
                ->addFieldToFilter('status', 1)
				->addFieldToFilter('store_id', array('in' => array(0, $storeId)))
                ->setOrder('title', 'ASC');
        return $collection;
    }

    public function getQuestionByTopicId($topicId) {
		$storeId = Mage::app()->getStore()->getId();
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
					->addFieldToFilter('store_id', array('in' => array(0, $storeId)))
                    ->setOrder($sort, 'DESC');
        } else {
            $collection = Mage::getModel('productquestions/question')->getCollection()
                    ->addFieldToFilter('topic_id', $topicId)
                    ->addFieldToFilter('status', 2)
                    ->addFieldToFilter('visibility', 1)
					->addFieldToFilter('store_id', array('in' => array(0, $storeId)))
                    ->setOrder($sort, 'DESC');
        }
        return $collection;
    }

    public function getQuestionByParams($topicId) {
		$storeId = Mage::app()->getStore()->getId();
        $isLoggedIn = Mage::getSingleton('customer/session')->isLoggedIn();
        $order = Mage::helper('productquestions')->sortBy();
        $query = $this->getRequest()->getParam('query');
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
                    ->setOrder($sort, 'DESC')
					->addFieldToFilter('store_id', array('in' => array(0, $storeId)))
                    ->addFieldToFilter('content', array('like' => '%' . $query . '%'));
        } else {
            $collection = Mage::getModel('productquestions/question')->getCollection()
                    ->addFieldToFilter('topic_id', $topicId)
                    ->addFieldToFilter('status', 2)
                    ->addFieldToFilter('visibility', 1)
                    ->setOrder($sort, 'DESC')
					->addFieldToFilter('store_id', array('in' => array(0, $storeId)))
                    ->addFieldToFilter('content', array('like' => '%' . $query . '%'));
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
