<?php

/* * ****************************************************
 * Package   : Event
 * Author    : HIEPNH
 * Copyright : (c) 2014
 * ***************************************************** */
?>
<?php

class MGS_Event_Block_Adminhtml_Event_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('event_form', array('legend' => Mage::helper('event')->__('Information')));

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('event')->__('Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'title',
        ));

        $fieldset->addField('image', 'image', array(
            'label' => Mage::helper('event')->__('Image'),
            'required' => false,
            'name' => 'image',
        ));

        $fieldset->addField('description', 'editor', array(
            'name' => 'description',
            'label' => Mage::helper('event')->__('Description'),
            'title' => Mage::helper('event')->__('Description'),
            'style' => 'width:700px; height:500px;',
            'wysiwyg' => true,
            'required' => true,
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('stores', 'multiselect', array(
                'label' => Mage::helper('event')->__('Store View'),
                'title' => Mage::helper('event')->__('Store View'),
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

        $fieldset->addField('location', 'textarea', array(
            'label' => Mage::helper('event')->__('Location'),
            'name' => 'location',
        ));

        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        $fieldset->addField('time_from', 'datetime', array(
            'name' => 'time_from',
            'class' => 'required-entry',
            'required' => true,
            'label' => Mage::helper('event')->__('Time From'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'format' => $dateFormatIso,
            'time' => true,
        ));

        $fieldset->addField('time_to', 'datetime', array(
            'name' => 'time_to',
            'class' => 'required-entry',
            'required' => true,
            'label' => Mage::helper('event')->__('Time To'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'format' => $dateFormatIso,
            'time' => true,
        ));

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('event')->__('Status'),
            'name' => 'status',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('event')->__('Enabled'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('event')->__('Disabled'),
                ),
            ),
        ));

        if (Mage::getSingleton('adminhtml/session')->getEventData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getEventData());
            Mage::getSingleton('adminhtml/session')->setEventData(null);
        } elseif (Mage::registry('event_data')) {
            $form->setValues(Mage::registry('event_data')->getData());
        }
        return parent::_prepareForm();
    }

}
