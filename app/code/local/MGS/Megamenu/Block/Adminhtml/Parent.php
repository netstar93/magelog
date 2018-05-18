<?php
class MGS_Megamenu_Block_Adminhtml_Parent extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	protected function _prepareLayout()
    {
        $this->setChild( 'grid',
            $this->getLayout()->createBlock('megamenu/adminhtml_parent_grid','adminhtml_parent.grid'));
    }
	
	public function __construct()
	  {
		$this->_controller = 'adminhtml_parent';
		$this->_blockGroup = 'megamenu';
		$this->_headerText = Mage::helper('megamenu')->__('Manage Menu');
		$this->_addButtonLabel = Mage::helper('megamenu')->__('Add Menu');
		parent::__construct();
	  }
}