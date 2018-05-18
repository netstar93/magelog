<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

class MGS_Brand_Block_Adminhtml_Brand_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('brand_form', array('legend' => Mage::helper('brand')->__('Item information')));

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('brand')->__('Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'title',
        ));

        $fieldset->addField('url_key', 'text', array(
            'label' => Mage::helper('brand')->__('URL Key'),
            'required' => false,
            'name' => 'url_key',
        ));

        $fieldset->addField('icon', 'image', array(
            'label' => Mage::helper('brand')->__('Logo'),
            'required' => false,
            'name' => 'icon',
        ));

        $fieldset->addField('image', 'image', array(
            'label' => Mage::helper('brand')->__('Image'),
            'required' => false,
            'name' => 'image',
        ));

        $fieldset->addField('description', 'editor', array(
            'name' => 'description',
            'label' => Mage::helper('brand')->__('Description'),
            'title' => Mage::helper('brand')->__('Description'),
            'style' => 'width:700px; height:250px;',
            'wysiwyg' => true,
            'required' => false,
        ));

        $fieldset->addField('meta_keywords', 'textarea', array(
            'name' => 'meta_keywords',
            'label' => Mage::helper('brand')->__('Meta Keywords'),
            'title' => Mage::helper('brand')->__('Meta Keywords'),
            'required' => false,
        ));

        $fieldset->addField('meta_description', 'textarea', array(
            'name' => 'meta_description',
            'label' => Mage::helper('brand')->__('Meta Description'),
            'title' => Mage::helper('brand')->__('Meta Description'),
            'required' => false,
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('stores', 'multiselect', array(
                'label' => Mage::helper('brand')->__('Store View'),
                'title' => Mage::helper('brand')->__('Store View'),
                'required' => true,
                'name' => 'stores[]',
                'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
                'value' => 'stores'
            ));
        } else {
            $fieldset->addField('stores', 'hidden', array(
                'name' => 'stores[]',
                'value' => Mage::app()->getStore(true)->getId()
            ));
        }

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('brand')->__('Status'),
            'name' => 'brand_status',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('brand')->__('Enabled'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('brand')->__('Disabled'),
                ),
            ),
        ));

        $fieldset->addField('is_featured', 'select', array(
            'label' => Mage::helper('brand')->__('Is Featured'),
            'name' => 'is_featured',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('brand')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('brand')->__('No'),
                ),
            ),
        ));

        $fieldset->addField('priority', 'text', array(
            'label' => Mage::helper('brand')->__('Sort Order'),
            'required' => false,
            'name' => 'priority',
        ));

        if (Mage::getSingleton('adminhtml/session')->getBrandData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getBrandData());
            Mage::getSingleton('adminhtml/session')->setBrandData(null);
        } elseif (Mage::registry('brand_data')) {
            $form->setValues(Mage::registry('brand_data')->getData());
        }
        return parent::_prepareForm();
    }

}
