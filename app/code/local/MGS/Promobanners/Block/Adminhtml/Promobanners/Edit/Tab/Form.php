<?php

class MGS_Promobanners_Block_Adminhtml_Promobanners_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('promobanners_form', array('legend'=>Mage::helper('promobanners')->__('Banner information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('promobanners')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('promobanners')->__('Background Image'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('promobanners')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('promobanners')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('promobanners')->__('Disabled'),
              ),
          ),
      ));
	  
	  $fieldset->addField('button', 'text', array(
          'label'     => Mage::helper('promobanners')->__('Button Text'),
          'name'      => 'button',
      ));
	  
	  $fieldset->addField('url', 'text', array(
          'label'     => Mage::helper('promobanners')->__('Link'),
          'name'      => 'url',
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('promobanners')->__('Html Content'),
          'title'     => Mage::helper('promobanners')->__('Html Content'),
          'style'     => 'width:700px; height:250px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
	  
	  $fieldset->addField('text_align', 'select', array(
          'label'     => Mage::helper('promobanners')->__('Text Align'),
          'name'      => 'text_align',
          'values'    => array(
              array(
                  'value'     => 'left',
                  'label'     => Mage::helper('promobanners')->__('Left'),
              ),

              array(
                  'value'     => 'center',
                  'label'     => Mage::helper('promobanners')->__('Center'),
              ),
			  
			  array(
                  'value'     => 'right',
                  'label'     => Mage::helper('promobanners')->__('Right'),
              ),
			  
			  array(
                  'value'     => 'top-left',
                  'label'     => Mage::helper('promobanners')->__('Top Left'),
              ),
			  
			  array(
                  'value'     => 'top-right',
                  'label'     => Mage::helper('promobanners')->__('Top Right'),
              ),
			  
			  array(
                  'value'     => 'bottom-left',
                  'label'     => Mage::helper('promobanners')->__('Bottom Left'),
              ),
			  
			  array(
                  'value'     => 'bottom-right',
                  'label'     => Mage::helper('promobanners')->__('Bottom Right'),
              )
          ),
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getPromobannersData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getPromobannersData());
          Mage::getSingleton('adminhtml/session')->setPromobannersData(null);
      } elseif ( Mage::registry('promobanners_data') ) {
          $form->setValues(Mage::registry('promobanners_data')->getData());
      }
      return parent::_prepareForm();
  }
}