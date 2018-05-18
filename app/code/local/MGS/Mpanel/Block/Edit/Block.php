<?php
class MGS_Mpanel_Block_Edit_Block extends Mage_Core_Block_Template
{
	protected $_block;
	
	public function __construct()
	{
		$blockId = $this->getRequest()->getParam('id');
		/* $this->_blockId = 'block'.$blockId;
		$this->_themeName = $this->getRequest()->getParam('layout');
		$storeId = Mage::app()->getStore()->getId(); */
		
		$block = Mage::getModel('mpanel/blocks')->load($blockId);
		$this->_block = $block;
	}
	
	
}