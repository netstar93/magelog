<?php
require_once 'app/code/core/Mage/Adminhtml/controllers/Catalog/ProductController.php';
class MGS_Deals_Adminhtml_ProductController extends Mage_Adminhtml_Catalog_ProductController
{
	public function indexAction()
    {
		if(Mage::getSingleton('adminhtml/session')->getRedirectDeal()){
			Mage::getSingleton('adminhtml/session')->setRedirectDeal(false);
			$this->_redirectUrl(Mage::helper('adminhtml')->getUrl('deals/adminhtml_deals/index'));
		}
		else{
			$this->_title($this->__('Catalog'))
				 ->_title($this->__('Manage Products'));
			$this->loadLayout();
			$this->renderLayout();
		}
    }
}