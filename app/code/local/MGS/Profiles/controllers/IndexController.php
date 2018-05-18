<?php
class MGS_Profiles_IndexController extends Mage_Core_Controller_Front_Action{
	
    public function indexAction() {
      
	  $this->loadLayout();   
	  $this->getLayout()->getBlock("head")->setTitle($this->__("Profiles"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
		   ));

      $breadcrumbs->addCrumb("profiles", array(
                "label" => $this->__("Profiles"),
                "title" => $this->__("Profiles")
		   ));

      $this->renderLayout(); 
	  
    }
	
	public function viewAction() {
        $id = $this->getRequest()->getParam('id');
		$profile = Mage::getModel('profiles/profile')->load($id);
		$this->loadLayout();
		
		$this->getLayout()->getBlock("head")->setTitle($profile->getName().' - '.$this->__("Profiles"));
		 
		$breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
		$breadcrumbs->addCrumb("home", array(
			"label" => $this->__("Home Page"),
			"title" => $this->__("Home Page"),
			"link"  => Mage::getBaseUrl()
		));

		$breadcrumbs->addCrumb("profiles", array(
			"label" => $this->__("Profiles"),
			"title" => $this->__("Profiles"),
			"link"  => Mage::getUrl('profiles')
		));
		$breadcrumbs->addCrumb("detail", array(
			"label" => $profile->getName(),
			"title" => $profile->getName()
		));

		$this->renderLayout(); 
	  
    }
}