<?php

class MGS_Megamenu_Block_Adminhtml_Parent_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

  protected function _prepareForm()
  {
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('megamenu_form', array('legend'=>Mage::helper('megamenu')->__('Item information')));

		$fieldset->addField('title', 'text', array(
		  'label'     => Mage::helper('megamenu')->__('Menu Name'),
		  'class'     => 'required-entry',
		  'required'  => true,
		  'name'      => 'title',
		));
		
		if($this->getRequest()->getParam('id')==1){
			$fieldset->addField('menu_type', 'select', array(
			  'label'     => Mage::helper('megamenu')->__('Menu Type'),
			  'name'      => 'menu_type',
			  'disabled'  => true,
			  'values'    => array(
				  array(
					  'value'     => 1,
					  'label'     => Mage::helper('megamenu')->__('Horizontal'),
				  ),

				  array(
					  'value'     => 2,
					  'label'     => Mage::helper('megamenu')->__('Vertical'),
				  ),
			  ),
			));
		}else{
			$fieldset->addField('menu_type', 'select', array(
			  'label'     => Mage::helper('megamenu')->__('Menu Type'),
			  'name'      => 'menu_type',
			  'values'    => array(
				  array(
					  'value'     => 1,
					  'label'     => Mage::helper('megamenu')->__('Horizontal'),
				  ),

				  array(
					  'value'     => 2,
					  'label'     => Mage::helper('megamenu')->__('Vertical'),
				  ),
			  ),
			));
		}
		
		$fieldset->addField('custom_class', 'text', array(
		  'label'     => Mage::helper('megamenu')->__('Custom Class'),
		  'name'      => 'custom_class',
		));
		
		if($this->getRequest()->getParam('id')==1){
			$fieldset->addField('status', 'select', array(
			  'label'     => Mage::helper('megamenu')->__('Status'),
			  'name'      => 'status',
			  'disabled'  => true,
			  'values'    => array(
				  array(
					  'value'     => 1,
					  'label'     => Mage::helper('megamenu')->__('Enabled'),
				  ),

				  array(
					  'value'     => 2,
					  'label'     => Mage::helper('megamenu')->__('Disabled'),
				  ),
			  ),
			));
		}else{
			$fieldset->addField('status', 'select', array(
			  'label'     => Mage::helper('megamenu')->__('Status'),
			  'name'      => 'status',
			  'values'    => array(
				  array(
					  'value'     => 1,
					  'label'     => Mage::helper('megamenu')->__('Enabled'),
				  ),

				  array(
					  'value'     => 2,
					  'label'     => Mage::helper('megamenu')->__('Disabled'),
				  ),
			  ),
			));
		}
		
     
      if ( Mage::getSingleton('adminhtml/session')->getParentData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getParentData());
          Mage::getSingleton('adminhtml/session')->setParentData(null);
      } elseif ( Mage::registry('parent_data') ) {
          $form->setValues(Mage::registry('parent_data')->getData());
      }
      return parent::_prepareForm();
  }
}