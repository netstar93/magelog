<?php

class MGS_Promobanners_Block_Adminhtml_Promobanners_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'promobanners';
        $this->_controller = 'adminhtml_promobanners';
        
        $this->_updateButton('save', 'label', Mage::helper('promobanners')->__('Save Banner'));
        $this->_updateButton('delete', 'label', Mage::helper('promobanners')->__('Delete Banner'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('promobanners_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'promobanners_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'promobanners_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('promobanners_data') && Mage::registry('promobanners_data')->getId() ) {
            return Mage::helper('promobanners')->__("Edit Banner '%s'", $this->htmlEscape(Mage::registry('promobanners_data')->getTitle()));
        } else {
            return Mage::helper('promobanners')->__('Add Banner');
        }
    }
}