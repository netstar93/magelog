<?php

class MGS_Lookbook_Block_Adminhtml_Slide_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

  protected function _prepareForm()
  {
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('lookbook_form', array('legend'=>Mage::helper('lookbook')->__('General information')));

		$fieldset->addField('title', 'text', array(
		  'label'     => Mage::helper('lookbook')->__('Slide Name'),
		  'class'     => 'required-entry',
		  'required'  => true,
		  'name'      => 'title',
		));
		
		$fieldset->addField('custom_class', 'text', array(
		  'label'     => Mage::helper('lookbook')->__('Custom class'),
		  'name'      => 'custom_class',
		));
		
		$fieldset->addField('navigation', 'select', array(
		  'label'     => Mage::helper('lookbook')->__('Show navigation'),
		  'name'      => 'navigation',
		  'values'    => array(
			  array(
				  'value'     => 0,
				  'label'     => Mage::helper('lookbook')->__('Use general config'),
			  ),
			  
			  array(
				  'value'     => 1,
				  'label'     => Mage::helper('lookbook')->__('Yes'),
			  ),
			  
			  array(
				  'value'     => 2,
				  'label'     => Mage::helper('lookbook')->__('No'),
			  ),
		  ),
		));
		
		$fieldset->addField('pagination', 'select', array(
		  'label'     => Mage::helper('lookbook')->__('Show pagination'),
		  'name'      => 'pagination',
		  'values'    => array(
			  array(
				  'value'     => 0,
				  'label'     => Mage::helper('lookbook')->__('Use general config'),
			  ),
			  
			  array(
				  'value'     => 1,
				  'label'     => Mage::helper('lookbook')->__('Yes'),
			  ),
			  
			  array(
				  'value'     => 2,
				  'label'     => Mage::helper('lookbook')->__('No'),
			  ),
		  ),
		));
		
		$fieldset->addField('auto_play', 'select', array(
		  'label'     => Mage::helper('lookbook')->__('Autoplay'),
		  'name'      => 'auto_play',
		  'values'    => array(
			  array(
				  'value'     => 0,
				  'label'     => Mage::helper('lookbook')->__('Use general config'),
			  ),
			  
			  array(
				  'value'     => 1,
				  'label'     => Mage::helper('lookbook')->__('Yes'),
			  ),
			  
			  array(
				  'value'     => 2,
				  'label'     => Mage::helper('lookbook')->__('No'),
			  ),
		  ),
		));
		
		$fieldset->addField('auto_play_timeout', 'text', array(
		  'label'     => Mage::helper('lookbook')->__('Autoplay interval timeout'),
		  'name'      => 'auto_play_timeout',
		  'class'     => 'validate-number'
		));
		
		$fieldset->addField('stop_auto', 'select', array(
		  'label'     => Mage::helper('lookbook')->__('Pause on mouse hover'),
		  'name'      => 'stop_auto',
		  'values'    => array(
			  array(
				  'value'     => 0,
				  'label'     => Mage::helper('lookbook')->__('Use general config'),
			  ),
			  
			  array(
				  'value'     => 1,
				  'label'     => Mage::helper('lookbook')->__('Yes'),
			  ),
			  
			  array(
				  'value'     => 2,
				  'label'     => Mage::helper('lookbook')->__('No'),
			  ),
		  ),
		));
		
		$fieldset->addField('loop', 'select', array(
		  'label'     => Mage::helper('lookbook')->__('Infinity loop'),
		  'name'      => 'loop',
		  'values'    => array(
			  array(
				  'value'     => 0,
				  'label'     => Mage::helper('lookbook')->__('Use general config'),
			  ),
			  
			  array(
				  'value'     => 1,
				  'label'     => Mage::helper('lookbook')->__('Yes'),
			  ),
			  
			  array(
				  'value'     => 2,
				  'label'     => Mage::helper('lookbook')->__('No'),
			  ),
		  ),
		));
		
		$fieldset->addField('next_image', 'image', array(
			  'label'     => Mage::helper('lookbook')->__('Next icon'),
			  'name'      => 'next_image',
			  'after_element_html' => '<p class="note">'.Mage::helper('lookbook')->__('Blank fo use general config.').'</p>'
		  ));
		  
		$fieldset->addField('prev_image', 'image', array(
			  'label'     => Mage::helper('lookbook')->__('Previous icon'),
			  'name'      => 'prev_image',
			  'after_element_html' => '<p class="note">'.Mage::helper('lookbook')->__('Blank fo use general config.').'</p><script type="text/javascript">$("auto_play_timeout").setAttribute("placeholder","'.Mage::helper('lookbook')->__('Use general config').'");</script>'
		  ));
		
		$fieldset->addField('status', 'select', array(
		  'label'     => Mage::helper('lookbook')->__('Status'),
		  'name'      => 'slider_status',
		  'values'    => array(
			  array(
				  'value'     => 1,
				  'label'     => Mage::helper('lookbook')->__('Enabled'),
			  ),

			  array(
				  'value'     => 2,
				  'label'     => Mage::helper('lookbook')->__('Disabled'),
			  ),
		  ),
		));
		
     
      if ( Mage::getSingleton('adminhtml/session')->getSlideData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getSlideData());
          Mage::getSingleton('adminhtml/session')->setSlideData(null);
      } elseif ( Mage::registry('slide_data') ) {
		  $formData = Mage::registry('slide_data')->getData();
		  $formData['slider_status'] = $formData['status'];
          $form->setValues($formData);
      }
      return parent::_prepareForm();
  }
}