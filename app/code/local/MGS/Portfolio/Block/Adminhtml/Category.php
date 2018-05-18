<?php
class MGS_Portfolio_Block_Adminhtml_Category extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	protected function _prepareLayout()
    {
        $this->setChild( 'grid',
            $this->getLayout()->createBlock('portfolio/adminhtml_category_grid','adminhtml_category.grid'));
    }
	
	public function __construct()
	  {
		$this->_controller = 'adminhtml_category';
		$this->_blockGroup = 'portfolio';
		$this->_headerText = Mage::helper('portfolio')->__('Manage Category');
		$this->_addButtonLabel = Mage::helper('portfolio')->__('Add Category');
		parent::__construct();
	  }
}