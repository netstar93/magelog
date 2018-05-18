<?php

class MGS_Lookbook_Block_Adminhtml_Slide_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'lookbook';
        $this->_controller = 'adminhtml_lookbook_slide';
        $this->_mode = 'edit';
        
        $this->_updateButton('save', 'label', Mage::helper('lookbook')->__('Save Slider'));
        $this->_updateButton('delete', 'label', Mage::helper('lookbook')->__('Delete Slider'));
		
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
	
	protected function _prepareLayout()
	{
		$this->setChild('form', $this->getLayout()->createBlock('lookbook/adminhtml_slide_edit_form'));
		return parent::_prepareLayout();
	}

    public function getHeaderText()
    {
        if( Mage::registry('slide_data') && Mage::registry('slide_data')->getId() ) {
            return Mage::helper('lookbook')->__("Edit Slider '%s'", $this->htmlEscape(Mage::registry('slide_data')->getTitle()));
        } else {
            return Mage::helper('lookbook')->__('Add Slider');
        }
    }
}