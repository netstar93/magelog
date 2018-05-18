<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Block_Adminhtml_Topic_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'productquestions';
        $this->_controller = 'adminhtml_topic';

        $this->_updateButton('save', 'label', Mage::helper('productquestions')->__('Save Topic'));
        $this->_updateButton('delete', 'label', Mage::helper('productquestions')->__('Delete Topic'));

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
                ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('topic_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'topic_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'topic_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText() {
        if (Mage::registry('topic_data') && Mage::registry('topic_data')->getId()) {
            return Mage::helper('productquestions')->__("Edit Topic '%s'", $this->htmlEscape(Mage::registry('topic_data')->getTitle()));
        } else {
            return Mage::helper('productquestions')->__('Add Topic');
        }
    }

}
