<?php

/* * ****************************************************
 * Package   : Social
 * Author    : HIEPNH
 * Copyright : (c) 2014
 * ***************************************************** */
?>
<?php

class MGS_Social_Helper_Facebooklikebox extends Mage_Core_Helper_Abstract {

    const XML_PATH_FACEBOOK_LIKE_BOX = 'social/facebook_like_box/';
    const XML_PATH_FACEBOOK_LIKE_BOX_ACTIVE = 'social/facebook_like_box/active';

    public function getFacebookLikeBoxConfig($code, $store = null) {
        if (!$store) {
            $store = Mage::app()->getStore()->getId();
        }
        return Mage::getStoreConfig(self::XML_PATH_FACEBOOK_LIKE_BOX . $code, $store);
    }

    public function isActived($store = null) {
        return Mage::getStoreConfigFlag(self::XML_PATH_FACEBOOK_LIKE_BOX_ACTIVE, $store);
    }

}
