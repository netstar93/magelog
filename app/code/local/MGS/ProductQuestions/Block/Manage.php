<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Block_Manage extends Mage_Core_Block_Template {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('productquestions/manage.phtml');
        $this->setQuestions($this->getQuestionCollection());
        $this->setAnswers($this->getAnswerCollection());
        Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('root')->setHeaderTitle(Mage::helper('productquestions')->__('Product Questions'));
    }

    protected function _prepareLayout() {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock('page/html_pager', 'manage.questions.pager')
                ->setCollection($this->getQuestions());
        $this->setChild('pager', $pager);
        $pagerAnswer = $this->getLayout()->createBlock('page/html_pager', 'manage.answers.pager')
                ->setCollection($this->getAnswers());
        $this->setChild('pager_answer', $pagerAnswer);
        $this->getQuestions()->load();
        $this->getAnswers()->load();
        return $this;
    }

    public function getPagerHtml() {
        return $this->getChildHtml('pager');
    }

    public function getPagerAnswerHtml() {
        return $this->getChildHtml('pager_answer');
    }

    public function getBackUrl() {
        return $this->getUrl('customer/account/');
    }

    public function getQuestionCollection() {
        $currentCustomer = Mage::getSingleton('customer/session')->getCustomer();
        $email = $currentCustomer->getData('email');
        $collection = Mage::getModel('productquestions/question')->getCollection()
                ->addFieldToFilter('customer_email', array('eq' => $email));
        return $collection;
    }

    public function getAnswerCollection() {
        $currentCustomer = Mage::getSingleton('customer/session')->getCustomer();
        $email = $currentCustomer->getData('email');
        $collection = Mage::getModel('productquestions/answer')->getCollection()
                ->addFieldToFilter('a_customer_email', array('eq' => $email));
        return $collection;
    }

    public function getAnswersByQuestionId($questionId) {
        $collection = Mage::getModel('productquestions/answer')->getCollection()
                ->addFieldToFilter('question_id', array('eq' => $questionId));
        return $collection;
    }

}
