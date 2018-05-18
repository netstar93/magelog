<?php

class MGS_Portfolio_Block_Adminhtml_Portfolio_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'portfolio';
        $this->_controller = 'adminhtml_portfolio';
        
        $this->_updateButton('save', 'label', Mage::helper('portfolio')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('portfolio')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('portfolio_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'portfolio_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'portfolio_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('portfolio_data') && Mage::registry('portfolio_data')->getId() ) {
            return Mage::helper('portfolio')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('portfolio_data')->getName()));
        } else {
            return Mage::helper('portfolio')->__('Add Item');
        }
    }
}