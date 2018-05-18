<?php

class MGS_Megamenu_Block_Adminhtml_Parent_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'megamenu';
        $this->_controller = 'adminhtml_parent';
        
        $this->_updateButton('save', 'label', Mage::helper('megamenu')->__('Save Menu'));
        $this->_updateButton('delete', 'label', Mage::helper('megamenu')->__('Delete Menu'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);
		
		if($this->getRequest()->getParam('id')==1){
			$this->_removeButton('delete');
			$this->_removeButton('reset');
		}
		
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
        if( Mage::registry('parent_data') && Mage::registry('parent_data')->getId() ) {
            return Mage::helper('megamenu')->__("Edit Menu '%s'", $this->htmlEscape(Mage::registry('parent_data')->getTitle()));
        } else {
            return Mage::helper('megamenu')->__('Add Menu');
        }
    }
}