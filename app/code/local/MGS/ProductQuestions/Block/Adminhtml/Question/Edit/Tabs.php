<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Block_Adminhtml_Question_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('question_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('productquestions')->__('Question Information'));
    }

    protected function _beforeToHtml() {
        $this->addTab('general', array(
            'label' => Mage::helper('productquestions')->__('General'),
            'title' => Mage::helper('productquestions')->__('General'),
            'content' => $this->getLayout()->createBlock('productquestions/adminhtml_question_edit_tab_form')->toHtml(),
        ));
        if ($this->getRequest()->getParam('id')) {
            $this->addTab('answers', array(
                'label' => Mage::helper('productquestions')->__('Manage Answers'),
                'title' => Mage::helper('productquestions')->__('Manage Answers'),
                'content' => $this->getLayout()->createBlock('productquestions/adminhtml_question_edit_tab_answer')->setTemplate('productquestions/answer/grid.phtml')->toHtml(),
            ));
        }
        $this->addTab('sharing', array(
            'label' => Mage::helper('productquestions')->__('Sharing Question'),
            'title' => Mage::helper('productquestions')->__('Sharing Question'),
            'url' => $this->getUrl('*/*/sharing', array('_current' => true)),
            'class' => 'ajax',
        ));
        return parent::_beforeToHtml();
    }

}
