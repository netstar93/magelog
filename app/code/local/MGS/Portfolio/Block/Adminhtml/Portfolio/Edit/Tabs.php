<?php

class MGS_Portfolio_Block_Adminhtml_Portfolio_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('portfolio_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('portfolio')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('portfolio')->__('Item Information'),
          'title'     => Mage::helper('portfolio')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('portfolio/adminhtml_portfolio_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}