<?php

class MGS_Portfolio_Block_Adminhtml_Category_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

  protected function _prepareForm()
  {
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('portfolio_form', array('legend'=>Mage::helper('portfolio')->__('Item information')));

		$fieldset->addField('category_name', 'text', array(
		  'label'     => Mage::helper('portfolio')->__('Category Name'),
		  'class'     => 'required-entry',
		  'required'  => true,
		  'name'      => 'category_name',
		));
		
		$noticeMessage = Mage::helper('portfolio')->__('e.g. domain.com/portfo/<b>identifier</b>');
		$validationErrorMessage = addslashes(
            Mage::helper('portfolio')->__(
                "Please use only letters (a-z or A-Z), numbers (0-9) or symbols '-' and '_' in this field"
            )
        );
	  $fieldset->addField('identifier', 'text', array(
          'label'     => Mage::helper('portfolio')->__('Identifier'),
		  'class'     => 'required-entry portfolio-validate-identifier',
          'required'  => true,
          'name'      => 'identifier',
		  'after_element_html' => '<span class="hint">' . $noticeMessage . '</span>'
                     . "<script>
                        Validation.add(
                            'portfolio-validate-identifier',
                            '" . $validationErrorMessage . "',
                            function(v, elm) {
                                var regex = new RegExp(/^[a-zA-Z0-9_-]+$/);
                                return v.match(regex);
                            }
                        );
                        </script>",
            )
      );
		
		
     
      if ( Mage::getSingleton('adminhtml/session')->getParentData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getParentData());
          Mage::getSingleton('adminhtml/session')->setParentData(null);
      } elseif ( Mage::registry('category_data') ) {
          $form->setValues(Mage::registry('category_data')->getData());
      }
      return parent::_prepareForm();
  }
}