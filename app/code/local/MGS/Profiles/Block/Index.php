<?php

class MGS_Profiles_Block_Index extends Mage_Core_Block_Template {

    public function getProfiles() {
        $profiles = Mage::getModel('profiles/profile')->getCollection()
                ->addFieldToFilter('status', 0);
		foreach($profiles as $profile) {
			$profile->setAddress($this->getUrl($this->helper('profiles')->setUrl($profile)));
		}
        return $profiles;
    }

}
