<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_FaqController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        $this->loadLayout();
        if (Mage::helper('productquestions')->faqsPageMetaKeywords() != '') {
            $this->getLayout()->getBlock('head')->setKeywords(Mage::helper('productquestions')->faqsPageMetaKeywords());
        }
        if (Mage::helper('productquestions')->faqsPageMetaDescription() != '') {
            $this->getLayout()->getBlock('head')->setDescription(Mage::helper('productquestions')->faqsPageMetaDescription());
        }
        $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
        $breadcrumbs->addCrumb('home', array('label' => Mage::helper('cms')->__('Home'), 'title' => Mage::helper('cms')->__('Go to Home Page'), 'link' => Mage::getBaseUrl()));
        $breadcrumbs->addCrumb('faqs', array('label' => Mage::helper('productquestions')->faqsPageTitle(), 'title' => Mage::helper('productquestions')->faqsPageTitle()));
        $this->renderLayout();
    }

    public function viewAction() {
        $this->loadLayout();
        $topicId = $this->getRequest()->getParam('topic');
        $topic = Mage::getModel('productquestions/topic')->load($topicId);
        if ($topic->getId()) {
            $this->getLayout()->getBlock('head')->setTitle($this->__($topic->getTitle()));
            $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
            $breadcrumbs->addCrumb('home', array('label' => Mage::helper('cms')->__('Home'), 'title' => Mage::helper('cms')->__('Go to Home Page'), 'link' => Mage::getBaseUrl()));
            $breadcrumbs->addCrumb('faqs', array('label' => Mage::helper('productquestions')->faqsPageTitle(), 'title' => Mage::helper('productquestions')->faqsPageTitle(), 'link' => Mage::helper('productquestions')->faqsPageUrl()));
            $breadcrumbs->addCrumb('topic', array('label' => $topic->getTitle(), 'title' => $topic->getTitle()));
        }
        $this->renderLayout();
    }

}
