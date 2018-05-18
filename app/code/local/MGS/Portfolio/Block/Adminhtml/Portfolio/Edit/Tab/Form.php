<?php

class MGS_Portfolio_Block_Adminhtml_Portfolio_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('portfolio_form', array('legend'=>Mage::helper('portfolio')->__('Item information')));
     
		
	  $fieldset->addField('name', 'text', array(
		  'label'     => Mage::helper('portfolio')->__('Name'),
		  'class'     => 'required-entry',
		  'required'  => true,
		  'name'      => 'name',
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
	  
	  $category = Mage::getModel('portfolio/category')->getCollection();
	  
	  $options = array();
				
	  if(count($category)>0){
		  foreach($category as $_category){
			  $options[] = array(
					'label' => $_category->getCategoryName(),
					'value' => $_category->getId()
				);
		  }
	  }
	  
	  $fieldset->addField('category_id', 'multiselect', array(
			'name' => 'category[]',
			'label' => Mage::helper('portfolio')->__('Category'),
			'title' => Mage::helper('portfolio')->__('Category'),
			'values' => $options,
			'style' => 'height:150px',
		));

      $fieldset->addField('thumbnail_image', 'image', array(
          'label'     => Mage::helper('portfolio')->__('Thumbnail Image'),
          'required'  => false,
          'name'      => 'thumbnail_image',
	  ));
	  
	  $fieldset->addField('base_image', 'image', array(
          'label'     => Mage::helper('portfolio')->__('Base Image'),
          'required'  => false,
          'name'      => 'base_image',
	  ));
	  
	  $fieldset->addField('client', 'text', array(
          'label'     => Mage::helper('portfolio')->__('Client'),
          'name'      => 'client',
      ));
	  
	  
	  
	  $fieldset->addField('services', 'text', array(
          'label'     => Mage::helper('portfolio')->__('Project'),
          'name'      => 'services',
      ));
	  
	  $fieldset->addField('project_url', 'text', array(
          'label'     => Mage::helper('portfolio')->__('Project Url'),
          'name'      => 'project_url',
      ));
	  
	  $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        $fieldset->addField('portfolio_date', 'date', array(
            'name'   => 'portfolio_date',
            'label'  => Mage::helper('catalogrule')->__('Date'),
            'title'  => Mage::helper('catalogrule')->__('Date'),
            'image'  => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format'       => $dateFormatIso
        ));
	  
	  $fieldset->addField('skills', 'text', array(
          'label'     => Mage::helper('portfolio')->__('Skills'),
          'name'      => 'skills',
      ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('portfolio')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('portfolio')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('portfolio')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('description', 'editor', array(
          'name'      => 'description',
          'label'     => Mage::helper('portfolio')->__('Description'),
          'title'     => Mage::helper('portfolio')->__('Description'),
          'style'     => 'width:700px; height:200px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getPortfolioData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getPortfolioData());
          Mage::getSingleton('adminhtml/session')->setPortfolioData(null);
      } elseif ( Mage::registry('portfolio_data') ) {
          $form->setValues(Mage::registry('portfolio_data')->getData());
      }
		/* $p = $form->getElement('thumbnail_image')->getValue();
		$form->getElement('thumbnail_image')->setValue('mgs/portfolio/thumbnail/' . $p);
		
		$p = $form->getElement('base_image')->getValue();
		$form->getElement('base_image')->setValue('mgs/portfolio/image/' . $p); */
      return parent::_prepareForm();
  }
}