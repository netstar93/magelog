<?php
class MGS_Megamenu_Block_Adminhtml_Megamenu extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_megamenu';
    $this->_blockGroup = 'megamenu';
    $this->_headerText = Mage::helper('megamenu')->__('Manage Megamenu Items');
    $this->_addButtonLabel = Mage::helper('megamenu')->__('Add Megamenu Item');
    parent::__construct();
  }
}