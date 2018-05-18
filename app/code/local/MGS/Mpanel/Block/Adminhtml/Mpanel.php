<?php
class MGS_Mpanel_Block_Adminhtml_Mpanel extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_mpanel';
    $this->_blockGroup = 'mpanel';
    $this->_headerText = Mage::helper('mpanel')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('mpanel')->__('Add Item');
    parent::__construct();
  }
}