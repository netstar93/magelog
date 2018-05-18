<?php
/**
 * General tab class file
 * 
 * @category    MGS
 * @package     MGS_Storelocator
 * @author      MGS Magento Team
 */

class MGS_Storelocator_Block_Adminhtml_Storelocator_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }
	
    protected function _prepareForm()
    {
        $model = Mage::registry('storelocator_data');
        $data = $model->getData();
        if ($data) {
            if(isset($data['store_logo'])) {
                $data['store_logo'] = 'mgs_storelocator/' . $data['store_logo'];
            }
        } else {
            $data = array();
        }
        
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('form_General', array('legend'=>Mage::helper('mgs_storelocator')->__('Store information')));
        
        if ($model->getId()) {
            $fieldset->addField('storelocator_id', 'hidden', array(
                'name' => 'storelocator_id',
            ));
        }

		$fieldset->addField('name', 'text', array(
          'label'     => Mage::helper('mgs_storelocator')->__('Store Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'name',
        ));
		
		$noticeMessage = Mage::helper('mgs_storelocator')->__('e.g. domain.com/storelocator/<b>identifier</b>');
		$validationErrorMessage = addslashes(
            Mage::helper('mgs_storelocator')->__(
                "Please use only letters (a-z or A-Z), numbers (0-9) or symbols '-' and '_' in this field"
            )
        );
		$fieldset->addField('identifier', 'text', array(
			'label'     => Mage::helper('mgs_storelocator')->__('Identifier'),
			'class'     => 'required-entry storelocator-validate-identifier',
			'required'  => true,
			'name'      => 'identifier',
			'after_element_html' => '<span class="hint">' . $noticeMessage . '</span>'
                     . "<script>
                        Validation.add(
                            'storelocator-validate-identifier',
                            '" . $validationErrorMessage . "',
                            function(v, elm) {
                                var regex = new RegExp(/^[a-zA-Z0-9_-]+$/);
                                return v.match(regex);
                            }
                        );
                        </script>",
            )
		);
		
        if (!Mage::app()->isSingleStoreMode()) {
            $field = $fieldset->addField('store_id', 'multiselect', array(
                'name'      => 'stores[]',
                'label'     => Mage::helper('mgs_storelocator')->__('Store View'),
                'title'     => Mage::helper('mgs_storelocator')->__('Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
            
            $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
            $field->setRenderer($renderer);
        }
        else {
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId(),
            ));
        }
        
        $fieldset->addField('store_logo', 'image', array(
          'label'     => Mage::helper('mgs_storelocator')->__('Store Image'),
          'name'      => 'store_logo',
          'note'      => 'Allowed extensions are jpg, jpeg, gif, png',
        ));
		
		$fieldset->addField('email', 'text', array(
          'label'     => Mage::helper('mgs_storelocator')->__('Email'),
          'class'     => 'validate-email',
          'name'      => 'email',
        ));
        
        $fieldset->addField('phone', 'text', array(
          'label'     => Mage::helper('mgs_storelocator')->__('Phone'),
          'name'      => 'phone',
        ));
        
        $fieldset->addField('fax', 'text', array(
          'label'     => Mage::helper('mgs_storelocator')->__('Fax'),
          'name'      => 'fax',
        ));        
        
        $fieldset->addField('url', 'text', array(
          'label'     => Mage::helper('mgs_storelocator')->__('Website Url'),
          'name'      => 'url',
        ));
		
		$enabledisable = Mage::getModel('adminhtml/system_config_source_enabledisable')->toOptionArray();
		$fieldset->addField('status', 'select', array(
            'name' => 'status',
            'label' => Mage::helper('mgs_storelocator')->__('Status'),
            'title' => Mage::helper('mgs_storelocator')->__('Status'),
            'values' => $enabledisable,
        ));
		
		$descriptionFieldset = $form->addFieldset('form_description', array('legend'=>Mage::helper('mgs_storelocator')->__('Store Description')));
		
		$wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
            array('tab_id' => $this->getTabId())
        );
        
        $descriptionFieldset->addField('description', 'editor', array(
            'label'     => Mage::helper('mgs_storelocator')->__('Store Description'),
            'title' => Mage::helper('mgs_storelocator')->__('Store Description'),
            'name'      => 'description',
            'config'    => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
            'style'     => 'height:20em; width:53em',
        ));
		
		$descriptionFieldset->addField('trading_hours', 'editor', array(
            'label'     => Mage::helper('mgs_storelocator')->__('Store Openning Hours'),
            'title' => Mage::helper('mgs_storelocator')->__('Store Openning Hours'),
            'name'      => 'trading_hours',
            'config'    => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
            'style'     => 'height:20em; width:53em',
        ));
        
        if(!empty($data)) {
            $form->setValues($data);
        }
       return parent::_prepareForm();
    }
}