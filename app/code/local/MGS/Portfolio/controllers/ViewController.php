<?php
class MGS_Portfolio_ViewController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
		if($id = $this->getRequest()->getParam('id')){
			$this->loadLayout();
			$model = Mage::getModel('portfolio/portfolio')->load($id);
			$this->getLayout()->getBlock('head')->setTitle($this->__('%s - Portfolio', $model->getName()));
			
			$breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
			$breadcrumbs->addCrumb('home', array('label'=>Mage::helper('cms')->__('Home'), 'title'=>Mage::helper('cms')->__('Home Page'), 'link'=>Mage::getBaseUrl()));
			$breadcrumbs->addCrumb('portfolio', array('label'=>Mage::helper('cms')->__('Portfolio'), 'title'=>Mage::helper('cms')->__('Portfolio'), 'link'=>Mage::getUrl('portfolios')));
			$breadcrumbs->addCrumb('item', array('label'=>$model->getName(), 'title'=>$model->getName()));
			
			$this->renderLayout();
		}else{
			$this->_redirectUrl(Mage::getBaseUrl());
			return;
		}
    }
}