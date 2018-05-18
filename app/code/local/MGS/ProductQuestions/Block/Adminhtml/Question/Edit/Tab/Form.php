<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Block_Adminhtml_Question_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('question_form', array('legend' => Mage::helper('productquestions')->__('Question Details')));

        $fieldset->addField('product_id', 'text', array(
            'label' => Mage::helper('productquestions')->__('Product ID'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'product_id'
        ));

        $fieldset->addField('customer_name', 'text', array(
            'label' => Mage::helper('productquestions')->__('Customer Name'),
            'required' => true,
            'class' => 'required-entry',
            'name' => 'customer_name',
        ));

        $fieldset->addField('customer_email', 'text', array(
            'label' => Mage::helper('productquestions')->__('Customer Email'),
            'required' => true,
            'class' => 'required-entry validate-email',
            'name' => 'customer_email',
        ));
		
		if (!Mage::app()->isSingleStoreMode()) {
            $field =$fieldset->addField('store_id', 'select', array(
                'name'      => 'store_id',
                'label'     => Mage::helper('cms')->__('Store View'),
                'title'     => Mage::helper('cms')->__('Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
            $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
            $field->setRenderer($renderer);
        }

        $fieldset->addField('content', 'textarea', array(
            'label' => Mage::helper('productquestions')->__('Content'),
            'required' => true,
            'class' => 'required-entry',
            'name' => 'content',
        ));

        $fieldset->addField('topic_id', 'select', array(
            'label' => Mage::helper('productquestions')->__('Topic'),
            'name' => 'topic_id',
            'values' => Mage::getSingleton('productquestions/topic')->getOptionArray(),
        ));

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('productquestions')->__('Status'),
            'name' => 'status',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('productquestions')->__('Pending'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('productquestions')->__('Approved'),
                ),
                array(
                    'value' => 3,
                    'label' => Mage::helper('productquestions')->__('Declined'),
                ),
            ),
        ));

        $fieldset->addField('visibility', 'select', array(
            'label' => Mage::helper('productquestions')->__('Visibility'),
            'name' => 'visibility',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('productquestions')->__('Public'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('productquestions')->__('Private'),
                ),
            ),
        ));

        $fieldset->addField('score', 'text', array(
            'label' => Mage::helper('productquestions')->__('Score'),
            'name' => 'score',
            'class' => 'validate-not-negative-number',
        ));

        if (Mage::getSingleton('adminhtml/session')->getQuestionData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getQuestionData());
            Mage::getSingleton('adminhtml/session')->setQuestionData(null);
        } elseif (Mage::registry('question_data')) {
            $form->setValues(Mage::registry('question_data')->getData());
        }
        return parent::_prepareForm();
    }

}
