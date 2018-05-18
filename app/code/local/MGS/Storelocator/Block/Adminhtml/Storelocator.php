<?php
/**
 * Grid container file
 * 
 * @category    MGS
 * @package     MGS_Storelocator
 * @author      MGS Magento Team
 * 
 */
class MGS_Storelocator_Block_Adminhtml_Storelocator extends Mage_Adminhtml_Block_Widget_Grid_Container
{       
  public function __construct()
  {
    $this->_controller = 'adminhtml_storelocator';
    $this->_blockGroup = 'mgs_storelocator';
    $this->_headerText = Mage::helper('mgs_storelocator')->__('Manage Stores');
    
    parent::__construct();
    
    if (Mage::helper('mgs_storelocator/admin')->isActionAllowed('save')) {
        $this->_updateButton('add', 'label', Mage::helper('mgs_storelocator')->__('Add New Store'));
    } else {
        $this->_removeButton('add');
    }
  }
}