<?php

class MGS_Lookbook_Block_Adminhtml_Slide_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('lookbook_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('lookbook')->__('Lookbook Slide'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('lookbook')->__('General Information'),
          'title'     => Mage::helper('lookbook')->__('General Information'),
          'content'   => $this->getLayout()->createBlock('lookbook/adminhtml_slide_edit_tab_form')->toHtml(),
      ));
	  
	  $this->addTab('items', array(
            'label' => Mage::helper('lookbook')->__('Slides'),
            'title' => Mage::helper('lookbook')->__('Slides'),
            'url' => $this->getUrl('*/*/items', array('_current' => true)),
            'class' => 'ajax',
        ));
     
      return parent::_beforeToHtml();
  }
}