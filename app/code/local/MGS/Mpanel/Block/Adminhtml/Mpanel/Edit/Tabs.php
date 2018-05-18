<?php

class MGS_Mpanel_Block_Adminhtml_Mpanel_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('mpanel_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('mpanel')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('mpanel')->__('Item Information'),
          'title'     => Mage::helper('mpanel')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('mpanel/adminhtml_mpanel_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}