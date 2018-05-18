<?php

/* * ****************************************************
 * Package   : Social
 * Author    : HIEPNH
 * Copyright : (c) 2014
 * ***************************************************** */
?>
<?php

class MGS_Social_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_GENERAL = 'social/general/';
    const XML_PATH_GENERAL_ACTIVE = 'social/general/active';

    public function getGeneralConfig($code, $store = null)
    {
        if (!$store) {
            $store = Mage::app()->getStore()->getId();
        }
        return Mage::getStoreConfig(self::XML_PATH_GENERAL . $code, $store);
    }

    public function isActived($store = null)
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_GENERAL_ACTIVE, $store);
    }

    public function redirect404($frontController)
    {
        $frontController->getResponse()
            ->setHeader('HTTP/1.1', '404 Not Found');
        $frontController->getResponse()
            ->setHeader('Status', '404 File not found');

        $pageId = Mage::getStoreConfig('web/default/cms_no_route');
        if (!Mage::helper('cms/page')->renderPage($frontController, $pageId)) {
            $frontController->_forward('defaultNoRoute');
        }
    }
}
