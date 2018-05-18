<?php

class MGS_Megamenu_Block_Adminhtml_Parent_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('megamenu_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('megamenu')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('megamenu')->__('General Information'),
          'title'     => Mage::helper('megamenu')->__('General Information'),
          'content'   => $this->getLayout()->createBlock('megamenu/adminhtml_parent_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}