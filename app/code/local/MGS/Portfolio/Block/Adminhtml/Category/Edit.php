<?php

class MGS_Portfolio_Block_Adminhtml_Category_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'portfolio';
        $this->_controller = 'adminhtml_category';
        
        $this->_updateButton('save', 'label', Mage::helper('portfolio')->__('Save Category'));
        $this->_updateButton('delete', 'label', Mage::helper('portfolio')->__('Delete Category'));
		
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
        if( Mage::registry('category_data') && Mage::registry('category_data')->getId() ) {
            return Mage::helper('portfolio')->__("Edit Category '%s'", $this->htmlEscape(Mage::registry('category_data')->getCategoryName()));
        } else {
            return Mage::helper('portfolio')->__('Add Category');
        }
    }
}