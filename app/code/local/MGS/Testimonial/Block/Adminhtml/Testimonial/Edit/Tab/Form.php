<?php

class MGS_Testimonial_Block_Adminhtml_Testimonial_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('testimonial_form', array('legend'=>Mage::helper('testimonial')->__('Item information')));
     
      $fieldset->addField('name', 'text', array(
          'label'     => Mage::helper('testimonial')->__('Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'name',
      ));
	  
	  $fieldset->addField('information', 'text', array(
          'label'     => Mage::helper('testimonial')->__('Information'),
          'name'      => 'information',
      ));

      $fieldset->addField('avatar', 'file', array(
          'label'     => Mage::helper('testimonial')->__('Avatar'),
          'required'  => false,
          'name'      => 'avatar',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('testimonial')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('testimonial')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('testimonial')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('testimonial')->__('Content'),
          'title'     => Mage::helper('testimonial')->__('Content'),
          'style'     => 'width:700px; height:220px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getTestimonialData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getTestimonialData());
          Mage::getSingleton('adminhtml/session')->setTestimonialData(null);
      } elseif ( Mage::registry('testimonial_data') ) {
          $form->setValues(Mage::registry('testimonial_data')->getData());
      }
      return parent::_prepareForm();
  }
}