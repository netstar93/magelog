<?php
class MGS_Abrands_Block_Adminhtml_Abrands extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_abrands';
    $this->_blockGroup = 'abrands';
    $this->_headerText = Mage::helper('abrands')->__('Manager Brands');
    $this->_addButtonLabel = Mage::helper('abrands')->__('Add Brand');
    parent::__construct();
  }
}