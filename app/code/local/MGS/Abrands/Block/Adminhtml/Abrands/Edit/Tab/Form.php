<?php

class MGS_Abrands_Block_Adminhtml_Abrands_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{	
	
	 
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('abrands_form', array('legend'=>Mage::helper('abrands')->__('Item information')));
	  
		$attribute = Mage::getModel('eav/entity_attribute')
			->getCollection()->addFieldToFilter('attribute_code', Mage::getStoreConfig('mpanel/catalog/brands'));
			
		$code= array ();
		foreach ($attribute as $item) {
			$code[$item->getAttributeCode()]=$item->getFrontendLabel();		
		}
	   
		$fieldset->addfield('name_attr', 'select', array(
         'label'     => mage::helper('abrands')->__('Attribute'),
          'name'      => 'name_attr',
		  'values'   => $code,
		  'onchange' =>'showOptions()',
		  
      ));
	  $fieldset->addfield('option_id', 'select', array(
         'label'     => mage::helper('abrands')->__('Option'),
          'name'      => 'option_id',
		  'values'   => '',
		  'onchange' =>'getOptionText()',
      ));  
	  
	  $fieldset->addfield('title', 'hidden', array(
			'name'=>'title'
      ));  
          
	  
      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('abrands')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
	  
	  $fieldset->addField('content', 'text', array(
          'name'      => 'content',
          'label'     => Mage::helper('abrands')->__('Link'),
          'title'     => Mage::helper('abrands')->__('Link'),
          'required'  => false,
      ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('abrands')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('abrands')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('abrands')->__('Disabled'),
              ),
          ),
      ));
     
     /*  $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('abrands')->__('Content'),
          'title'     => Mage::helper('abrands')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      )); */
     
      if ( Mage::getSingleton('adminhtml/session')->getAbrandsData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getAbrandsData());
          Mage::getSingleton('adminhtml/session')->setAbrandsData(null);
      } elseif ( Mage::registry('abrands_data') ) {
          $form->setValues(Mage::registry('abrands_data')->getData());
      }
      return parent::_prepareForm();
  }
}