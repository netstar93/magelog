<?php
class MGS_Deals_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
		$this->loadLayout();
		if(Mage::getStoreConfig('deals/deals_page/title')!=''){
			$this->getLayout()->getBlock('head')->setTitle(Mage::getStoreConfig('deals/deals_page/title'));	
		}
		if(Mage::getStoreConfig('deals/deals_page/meta_keyword')!=''){
			$this->getLayout()->getBlock('head')->setKeywords(Mage::getStoreConfig('deals/deals_page/meta_keyword'));
		}
		if(Mage::getStoreConfig('deals/deals_page/meta_description')!=''){
			$this->getLayout()->getBlock('head')->setDescription(Mage::getStoreConfig('deals/deals_page/meta_description'));
		}
		if(Mage::getStoreConfig('deals/deals_page/breadcrumbs')!=''){
			$breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
			$breadcrumbs->addCrumb('home', array('label'=>Mage::helper('cms')->__('Home'), 'title'=>Mage::helper('cms')->__('Home Page'), 'link'=>Mage::getBaseUrl()));
			$breadcrumbs->addCrumb('deals', array('label'=>Mage::getStoreConfig('deals/deals_page/title'), 'title'=>Mage::getStoreConfig('deals/deals_page/title')));
		}
		$this->renderLayout();
    }
}