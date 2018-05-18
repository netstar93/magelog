<?php

class MGS_Profiles_Block_Adminhtml_Profile_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {

        parent::__construct();
        $this->_objectId = "profile_id";
        $this->_blockGroup = "profiles";
        $this->_controller = "adminhtml_profile";
        $this->_updateButton("save", "label", Mage::helper("profiles")->__("Save Profile"));
        $this->_updateButton("delete", "label", Mage::helper("profiles")->__("Delete Profile"));

        $this->_addButton("saveandcontinue", array(
            "label" => Mage::helper("profiles")->__("Save And Continue Edit"),
            "onclick" => "saveAndContinueEdit()",
            "class" => "save",
                ), -100);



        $this->_formScripts[] = "

                function saveAndContinueEdit(){
                        editForm.submit($('edit_form').action+'back/edit/');
                }
        ";
    }

    public function getHeaderText() {
        if (Mage::registry("profile_data") && Mage::registry("profile_data")->getId()) {
            return Mage::helper("profiles")->__("Edit Profile '%s'", $this->htmlEscape(Mage::registry("profile_data")->getName()));
        } else {
            return Mage::helper("profiles")->__("Add Profile");
        }
    }

}
