<?php

class MGS_Abrands_Block_Adminhtml_Abrands_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'abrands';
        $this->_controller = 'adminhtml_abrands';
        
        $this->_updateButton('save', 'label', Mage::helper('abrands')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('abrands')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('abrands_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'abrands_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'abrands_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('abrands_data') && Mage::registry('abrands_data')->getId() ) {
            return Mage::helper('abrands')->__("Edit Brand '%s'", $this->htmlEscape(Mage::registry('abrands_data')->getTitle()));
        } else {
            return Mage::helper('abrands')->__('Add Brand');
        }
    }
}