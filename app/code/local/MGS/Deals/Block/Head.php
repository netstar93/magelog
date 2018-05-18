<?php
class MGS_Deals_Block_Head extends Mage_Core_Block_Template
{
	public function __construct()
    {
		if(Mage::getStoreConfig('deals/general/enabled')){
			Mage::helper('deals/data')->changeStatusOfCollection();
		}
    }
}