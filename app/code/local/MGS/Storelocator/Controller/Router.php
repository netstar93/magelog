<?php
class MGS_Storelocator_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract {

	public function initControllerRouters($observer){
        $front = $observer->getEvent()->getFront();
        $front->addRouter('storelocator', $this);
    }
	
    public function match(Zend_Controller_Request_Http $request){
        if (!Mage::isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }
		/* Get Url of page */
        $urlKey = trim($request->getPathInfo(), '/');;
		$parts = explode('/', $urlKey);
		if($parts[0] == 'storelocator'){
			/* Get Data have id = $parts[0] */
			$store = Mage::getModel('mgs_storelocator/storelocator')->getCollection()
					->addFieldToFilter('identifier', array('eq' => $parts[1]))
					->getFirstItem();
			if($store->getId()) {
				$request->setModuleName('storelocator')
				->setControllerName('index')
				->setActionName('view')
				->setParam('id', $store->getId());
			} else {
				return false;
			}
			$request->setAlias(
				Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
				$urlKey
			);
			return true;
		} else {
			return false;
		}
    }
}