<?php

class MGS_Mpanel_Block_Adminhtml_Mpanel_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'mpanel';
        $this->_controller = 'adminhtml_mpanel';
        
        $this->_updateButton('save', 'label', Mage::helper('mpanel')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('mpanel')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('mpanel_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'mpanel_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'mpanel_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('mpanel_data') && Mage::registry('mpanel_data')->getId() ) {
            return Mage::helper('mpanel')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('mpanel_data')->getTitle()));
        } else {
            return Mage::helper('mpanel')->__('Add Item');
        }
    }
}