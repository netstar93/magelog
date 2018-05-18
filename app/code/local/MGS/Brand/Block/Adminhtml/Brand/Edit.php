<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

class MGS_Brand_Block_Adminhtml_Brand_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'brand';
        $this->_controller = 'adminhtml_brand';

        $this->_updateButton('save', 'label', Mage::helper('brand')->__('Save'));
        $this->_updateButton('delete', 'label', Mage::helper('brand')->__('Delete'));

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
                ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('brand_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'brand_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'brand_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    protected function _prepareLayout() {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }

    public function getHeaderText() {
        if (Mage::registry('brand_data') && Mage::registry('brand_data')->getId()) {
            return Mage::helper('brand')->__("Edit Brand '%s'", $this->htmlEscape(Mage::registry('brand_data')->getTitle()));
        } else {
            return Mage::helper('brand')->__('Add New Brand');
        }
    }

}
