<?php

class MGS_Promobanners_Block_Adminhtml_Promobanners_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('promobanners_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('promobanners')->__('Banner Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('promobanners')->__('Banner Information'),
          'title'     => Mage::helper('promobanners')->__('Banner Information'),
          'content'   => $this->getLayout()->createBlock('promobanners/adminhtml_promobanners_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}