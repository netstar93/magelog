<?php
require_once('app/code/core/Mage/CatalogSearch/controllers/ResultController.php');
class MGS_Mpanel_CatalogSearch_ResultController extends Mage_CatalogSearch_ResultController
{
	
	public function indexAction()
	{
		
		if($this->getRequest()->isXmlHttpRequest()){ 
			//Check for AJAX query
			$response = array();
			
			$query = Mage::helper('catalogsearch')->getQuery();
			/* @var $query Mage_CatalogSearch_Model_Query */
			
			$query->setStoreId(Mage::app()->getStore()->getId());
			
			if ($query->getQueryText()) {
				if (Mage::helper('catalogsearch')->isMinQueryLength()) {
					$query->setId(0)
					->setIsActive(1)
					->setIsProcessed(1);
				}
				else {
					if ($query->getId()) {
						$query->setPopularity($query->getPopularity()+1);
					}else {
						$query->setPopularity(1);
					}
				
					if ($query->getRedirect()){
						$query->save();
						$this->getResponse()->setRedirect($query->getRedirect());
						return;
					}else {
						$query->prepare();
					}
				}
				
				Mage::helper('catalogsearch')->checkNotes();
				
				$this->loadLayout();
				$this->_initLayoutMessages('catalog/session');
				$this->_initLayoutMessages('checkout/session');
				
				if(Mage::getStoreConfig('mpanel/general/enabled') && (Mage::getStoreConfig('mpanel/catalogsearch/layout')!='')){
					$this->getLayout()->helper('page/layout')->applyTemplate(Mage::getStoreConfig('mpanel/catalogsearch/layout'));
				}
				
				$this->renderLayout();
				
				if (!Mage::helper('catalogsearch')->isMinQueryLength()) {
					$query->save();
				}
			}
			else {
				$this->_redirectReferer();
				$response['status'] = 'FAILURE'; //Add failure when filter can't be applied
			}
			
			$viewpanel = $this->getLayout()->getBlock('catalogsearch.leftnav')->toHtml(); //Get the new Layered Manu
			$productlist = $this->getLayout()->getBlock('search_result_list')->toHtml(); //New product List
			$response['status'] = 'SUCCESS'; //Send Success
			$response['leftcontent']=$viewpanel;
			$response['maincontent'] = $productlist;
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
			return;
			
		}

		
		
		
		$query = Mage::helper('catalogsearch')->getQuery();
		/* @var $query Mage_CatalogSearch_Model_Query */
		
		$query->setStoreId(Mage::app()->getStore()->getId());
		
		if ($query->getQueryText()) {
			if (Mage::helper('catalogsearch')->isMinQueryLength()) {
			$query->setId(0)
			->setIsActive(1)
			->setIsProcessed(1);
			}else {
				if ($query->getId()) {
					$query->setPopularity($query->getPopularity()+1);
				}else {
					$query->setPopularity(1);
				}
		
				if ($query->getRedirect()){
					$query->save();
					$this->getResponse()->setRedirect($query->getRedirect());
					return;
				}else {
					$query->prepare();
				}
			}
		
			Mage::helper('catalogsearch')->checkNotes();
			
			$this->loadLayout();
			$this->_initLayoutMessages('catalog/session');
			$this->_initLayoutMessages('checkout/session');
			
			if(Mage::getStoreConfig('mpanel/general/enabled') && (Mage::getStoreConfig('mpanel/catalogsearch/layout')!='')){
				$this->getLayout()->helper('page/layout')->applyTemplate(Mage::getStoreConfig('mpanel/catalogsearch/layout'));
			}
			
			$this->renderLayout();
			
			if (!Mage::helper('catalogsearch')->isMinQueryLength()) {
				$query->save();
			}
		}
		else {
			$this->_redirectReferer();
		}
	}
	
}
