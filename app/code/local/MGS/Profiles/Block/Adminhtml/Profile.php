<?php


class MGS_Profiles_Block_Adminhtml_Profile extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_profile";
	$this->_blockGroup = "profiles";
	$this->_headerText = Mage::helper("profiles")->__("Profile Manager");
	$this->_addButtonLabel = Mage::helper("profiles")->__("Add New Profile");
	parent::__construct();
	
	}

}