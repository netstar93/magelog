<?php
class MGS_Lookbook_Block_Adminhtml_Slide extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	protected function _prepareLayout()
    {
        $this->setChild('grid',
            $this->getLayout()->createBlock('lookbook/adminhtml_slide_grid','adminhtml_slide.grid'));
    }
	
	public function __construct()
	  {
		$this->_controller = 'adminhtml_lookbook_slide';
		$this->_blockGroup = 'lookbook.slide';
		$this->_headerText = Mage::helper('lookbook')->__('Manage Lookbook Slide');
		$this->_addButtonLabel = Mage::helper('lookbook')->__('Add Slide');
		parent::__construct();
	  }
}