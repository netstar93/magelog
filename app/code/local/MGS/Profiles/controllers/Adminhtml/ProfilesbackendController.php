<?php
class MGS_Profiles_Adminhtml_ProfilesbackendController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
       $this->loadLayout();
	   $this->_title($this->__("Profiles"));
	   $this->renderLayout();
    }
}