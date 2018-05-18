<?php

class MGS_Profiles_Block_View extends Mage_Core_Block_Template {
	
    public function getProfile(){
		$id = $this->getRequest()->getParam('id');
		$profile = Mage::getModel('profiles/profile')->load($id);
		return $profile;
	}

}
