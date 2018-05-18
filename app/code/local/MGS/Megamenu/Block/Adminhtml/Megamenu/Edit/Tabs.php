<?php

class MGS_Megamenu_Block_Adminhtml_Megamenu_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
          'content'   => $this->getLayout()->createBlock('megamenu/adminhtml_megamenu_edit_tab_form')->toHtml(),
      ));
	  
	  $this->addTab('category', array(
          'label'     => Mage::helper('megamenu')->__('Category'),
          'title'     => Mage::helper('megamenu')->__('Category'),
          'content'   => $this->getLayout()->createBlock('megamenu/adminhtml_megamenu_edit_tab_category')->toHtml(),
      ));
	  
	  $this->addTab('topbottom', array(
          'label'     => Mage::helper('megamenu')->__('Static Contents'),
          'title'     => Mage::helper('megamenu')->__('Static Contents'),
          'content'   => $this->getLayout()->createBlock('megamenu/adminhtml_megamenu_edit_tab_topbottom')->toHtml(),
      ));
	  
	  $this->addTab('static', array(
          'label'     => Mage::helper('megamenu')->__('Static Content'),
          'title'     => Mage::helper('megamenu')->__('Static Content'),
          'content'   => $this->getLayout()->createBlock('megamenu/adminhtml_megamenu_edit_tab_static')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}