<?php

class MGS_Maintanance_Model_Observer {

    public function initControllerRouters($request) {
        $adminFrontName = Mage::getConfig()->getNode('admin/routers/adminhtml/args/frontName');
        $area = Mage::app()->getRequest()->getOriginalPathInfo();
        if ((!preg_match('/' . $adminFrontName . '/', $area)) && (!preg_match('/checkTimer/', $area)) && Mage::app()->getRequest()->getBaseUrl() != '/downloader') {
            $storeId = Mage::app()->getStore()->getStoreId();
            $isEnabled = Mage::getStoreConfig('maintanance/general/enabled', $storeId);
            if ($isEnabled == 1) {
                $currentIP = $_SERVER['REMOTE_ADDR'];
                $allowForAdmin = Mage::getStoreConfig('maintanance/general/allowforadmin', $storeId);
                $adminIp = null;
                if ($allowForAdmin == 1) {
                    Mage::getSingleton('core/session', array('name' => 'adminhtml'));
                    $adminSession = Mage::getSingleton('admin/session');
                    if ($adminSession->isLoggedIn()) {
                        $adminIp = $adminSession['_session_validator_data']['remote_addr'];
                    }
                }
                if ($currentIP === $adminIp) {
                    $this->createLog('Access granted for admin with IP: ' . $currentIP . ' and store ' . $storeId, $storeId);
                } else {
                    $this->createLog('Access denied  for IP: ' . $currentIP . ' and store ' . $storeId, $storeId);
                    $html = Mage::getSingleton('core/layout')->createBlock('core/template')->setTemplate('mgs/maintanance/maintanance.phtml')->toHtml();
                    if ('' !== $html) {
                        Mage::getSingleton('core/session', array('name' => 'front'));
                        $response = $request->getEvent()->getFront()->getResponse();
                        $response->setHeader('HTTP/1.1', '503 Service Temporarily Unavailable');
                        $response->setHeader('Status', '503 Service Temporarily Unavailable');
                        $response->setHeader('Retry-After', '5000');
                        $response->setBody($html);
                        $response->sendHeaders();
                        $response->outputBody();
                    }
                    exit();
                }
            }
        }
    }

    private function createLog($text, $storeId = null, $zendLevel = Zend_Log::DEBUG) {
        $logFile = trim(Mage::getStoreConfig('maintanance/general/logFileName', $storeId));
        if ('' === $logFile) {
            $logFile = 'mgs_maintenance.log';
        }
        Mage::log($text, $zendLevel, $logFile);
    }

}
