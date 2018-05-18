<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Block_Adminhtml_Question_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'productquestions';
        $this->_controller = 'adminhtml_question';

        $this->_updateButton('save', 'label', Mage::helper('productquestions')->__('Save Question'));
        $this->_updateButton('delete', 'label', Mage::helper('productquestions')->__('Delete Question'));

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
                ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('question_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'question_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'question_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText() {
        if (Mage::registry('question_data') && Mage::registry('question_data')->getId()) {
            return Mage::helper('productquestions')->__("Edit Question '#%s' from %s &lt;%s&gt;", $this->htmlEscape(Mage::registry('question_data')->getId()), $this->htmlEscape(Mage::registry('question_data')->getCustomerName()), $this->htmlEscape(Mage::registry('question_data')->getCustomerEmail()));
        } else {
            return Mage::helper('productquestions')->__('Add Question');
        }
    }

}
