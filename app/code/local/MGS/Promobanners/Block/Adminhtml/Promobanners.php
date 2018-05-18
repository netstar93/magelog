<?php
class MGS_Promobanners_Block_Adminhtml_Promobanners extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_promobanners';
    $this->_blockGroup = 'promobanners';
    $this->_headerText = Mage::helper('promobanners')->__('Banners Manager');
    $this->_addButtonLabel = Mage::helper('promobanners')->__('Add Banner');
    parent::__construct();
  }
}