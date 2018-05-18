<?php
class MGS_Deals_Block_Adminhtml_Deals extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_deals';
    $this->_blockGroup = 'deals';
    $this->_headerText = Mage::helper('deals')->__('Deals Report');
    parent::__construct();
	$this->_removeButton('add');
  }
}