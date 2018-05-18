<?php
class MGS_Profiles_Helper_Data extends MGS_Mgscore_Helper_Data
{
	public function setUrl($profile = NULL) {
		return 'profile/'.$profile->getIden();
	}
}
	 