<?php

class MGS_Deals_Block_Adminhtml_Deals_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'deals';
        $this->_controller = 'adminhtml_deals';
        
        $this->_updateButton('save', 'label', Mage::helper('deals')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('deals')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('deals_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'deals_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'deals_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('deals_data') && Mage::registry('deals_data')->getId() ) {
            return Mage::helper('deals')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('deals_data')->getTitle()));
        } else {
            return Mage::helper('deals')->__('Add Item');
        }
    }
}