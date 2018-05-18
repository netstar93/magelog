<?php
class MGS_Portfolio_Block_Adminhtml_Portfolio extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_portfolio';
    $this->_blockGroup = 'portfolio';
    $this->_headerText = Mage::helper('portfolio')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('portfolio')->__('Add Item');
    parent::__construct();
  }
}