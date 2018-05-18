<?php
class MGS_Portfolio_CategoryController extends Mage_Core_Controller_Front_Action
{
    public function listAction()
    {
		$this->loadLayout();
		
		$breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
		$breadcrumbs->addCrumb('home', array('label'=>Mage::helper('cms')->__('Home'), 'title'=>Mage::helper('cms')->__('Home Page'), 'link'=>Mage::getBaseUrl()));
		
		if($id = $this->getRequest()->getParam('id')){
			$model = Mage::getModel('portfolio/category')->load($id);
			$this->getLayout()->getBlock('head')->setTitle($this->__('%s - Portfolio', $model->getCategoryName()));
			$breadcrumbs->addCrumb('portfolio', array('label'=>Mage::helper('cms')->__('Portfolio'), 'title'=>Mage::helper('cms')->__('Portfolio'), 'link'=>Mage::getUrl('portfolio/category/list')));
			$breadcrumbs->addCrumb('category', array('label'=>$model->getCategoryName(), 'title'=>$model->getCategoryName()));
			
		}else{
			$this->getLayout()->getBlock('head')->setTitle($this->__('Portfolio List'));
			$breadcrumbs->addCrumb('category', array('label'=>$this->__('Portfolio'), 'title'=>$this->__('Portfolio')));
		}
		$this->renderLayout();
    }
}