<?php
class MGS_Profiles_Block_Widget extends Mage_Core_Block_Template {
	
    public function getProfiles() {
		$profileIds = $this->getProfileIds();
		$profileIds = explode(',',$profileIds);
        $profiles = Mage::getModel('profiles/profile')->getCollection()
			->addFieldToFilter('profile_id', array('in'=>$profileIds))
			->addFieldToFilter('status', 0);
		foreach($profiles as $profile) {
			$profile->setAddress($this->getUrl($this->helper('profiles')->setUrl($profile)));
		}
        return $profiles;
    }

}
