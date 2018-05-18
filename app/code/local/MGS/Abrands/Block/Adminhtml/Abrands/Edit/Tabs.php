<?php

class MGS_Abrands_Block_Adminhtml_Abrands_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('abrands_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('abrands')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('abrands')->__('Item Information'),
          'title'     => Mage::helper('abrands')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('abrands/adminhtml_abrands_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}