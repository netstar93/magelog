<?php
class MGS_Profiles_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract {

	public function initControllerRouters($observer){
        $front = $observer->getEvent()->getFront();
        $front->addRouter('profile', $this);
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
		if($parts[0] == 'profile'){
			/* Get Data have id = $parts[0] */
			$port = Mage::getModel('profiles/profile')->getCollection()
					->addFieldToFilter('iden', array('eq' => $parts[1]))
					->getFirstItem();
			if($port->getId()) {
				$request->setModuleName('profiles')
				->setControllerName('index')
				->setActionName('view')
				->setParam('id', $port->getId());
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