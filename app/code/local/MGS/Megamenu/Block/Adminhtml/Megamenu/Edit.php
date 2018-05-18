<?php

class MGS_Megamenu_Block_Adminhtml_Megamenu_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'megamenu';
        $this->_controller = 'adminhtml_megamenu';
        
        $this->_updateButton('save', 'label', Mage::helper('megamenu')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('megamenu')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);
		
		$this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('static_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'static_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'static_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('megamenu_data') && Mage::registry('megamenu_data')->getId() ) {
            return Mage::helper('megamenu')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('megamenu_data')->getTitle()));
        } else {
            return Mage::helper('megamenu')->__('Add Item');
        }
    }
}