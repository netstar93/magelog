<?php
class MGS_Portfolio_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract {

	public function initControllerRouters($observer){
        $front = $observer->getEvent()->getFront();
        $front->addRouter('portfolio', $this);
    }
	
    public function match(Zend_Controller_Request_Http $request){
        if (!Mage::isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }
		//die();
		/* Get Url of page */
        $urlKey = trim($request->getPathInfo(), '/');;
		$parts = explode('/', $urlKey);
		if($parts[0] == 'portfolios'){
			/* Get Data have id = $parts[0] */
			if($parts[1]) {
				$port = Mage::getModel('portfolio/portfolio')->getCollection()
						->addFieldToFilter('identifier', array('eq' => $parts[1]))
						->getFirstItem();
				$portcate = Mage::getModel('portfolio/category')->getCollection()
						->addFieldToFilter('identifier', array('eq' => $parts[1]))
						->getFirstItem();
				if($port->getId()) {
					$request->setModuleName('portfolio')
					->setControllerName('view')
					->setActionName('index')
					->setParam('id', $port->getId());
				} elseif ($portcate->getCategoryId()) {				
					$request->setModuleName('portfolio')
						->setControllerName('category')
						->setActionName('list')
						->setParam('id', $portcate->getCategoryId());
				} else {
					return false;
				}
			} else {
				$request->setModuleName('portfolio')
					->setControllerName('category')
					->setActionName('list');
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