<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Block_Adminhtml_Topic_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('topic_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('productquestions')->__('Topic Information'));
    }

    protected function _beforeToHtml() {
        $this->addTab('general', array(
            'label' => Mage::helper('productquestions')->__('General'),
            'title' => Mage::helper('productquestions')->__('General'),
            'content' => $this->getLayout()->createBlock('productquestions/adminhtml_topic_edit_tab_form')->toHtml(),
        ));
        $this->addTab('question', array(
            'label' => Mage::helper('productquestions')->__('Assign Questions'),
            'title' => Mage::helper('productquestions')->__('Assign Questions'),
            'url' => $this->getUrl('*/*/question', array('_current' => true)),
            'class' => 'ajax',
        ));
        return parent::_beforeToHtml();
    }

}
