<?php

/* * ****************************************************
 * Package   : Event
 * Author    : HIEPNH
 * Copyright : (c) 2014
 * ***************************************************** */
?>
<?php

class MGS_Event_Helper_Data extends Mage_Core_Helper_Abstract {

    const XML_PATH_GENERAL = 'event/general/';
    const XML_PATH_GENERAL_ACTIVE = 'event/general/active';

    public function getGeneralConfig($code, $store = null) {
        if (!$store) {
            $store = Mage::app()->getStore()->getId();
        }
        return Mage::getStoreConfig(self::XML_PATH_GENERAL . $code, $store);
    }

    public function isActived($store = null) {
        return Mage::getStoreConfigFlag(self::XML_PATH_GENERAL_ACTIVE, $store);
    }

}
