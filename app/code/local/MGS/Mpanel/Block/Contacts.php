<?php
class MGS_Mpanel_Block_Contacts extends Mage_Core_Block_Template
{
	public function _construct()
    {
		$helper = Mage::helper('mpanel');
		if($helper->acceptToUsePanel()){
			$this->setTemplate('mgs/mpanel/gmap.phtml');
		}
		else{
			$this->setTemplate('contacts/map.phtml');
		}
    }
}