<?php

class MGS_Megamenu_Block_Adminhtml_Megamenu_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true); 
        }
    }

  protected function _prepareForm()
  {
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('megamenu_form', array('legend'=>Mage::helper('megamenu')->__('Item information')));
		
		$parents = Mage::getModel('megamenu/parent')->getCollection();
		$arrParent = array();
		foreach($parents as $_parent){
			$arrParent[] = array(
				'value'=>$_parent->getId(),
				'label'=>$_parent->getTitle()
			);
		}
		
		$fieldset->addField('parent_id', 'select', array(
		  'label'     => Mage::helper('megamenu')->__('Menu'),
		  'name'      => 'parent_id',
		  'values'    => $arrParent
		));
		

		$fieldset->addField('title', 'text', array(
		  'label'     => Mage::helper('megamenu')->__('Label'),
		  'class'     => 'required-entry',
		  'required'  => true,
		  'name'      => 'title',
		));
		
		$fieldset->addField('menu_type', 'select', array(
		  'label'     => Mage::helper('megamenu')->__('Menu Type'),
		  'name'      => 'menu_type',
		  'values'    => array(
			  array(
				  'value'     => 1,
				  'label'     => Mage::helper('megamenu')->__('Catalog Category'),
			  ),

			  array(
				  'value'     => 2,
				  'label'     => Mage::helper('megamenu')->__('Static Content'),
			  ),
		  ),
		));
		
		
		$fieldset->addField('url', 'text', array(
		  'label'     => Mage::helper('megamenu')->__('Link'),
		  'name'      => 'url',
		  'after_element_html' => '<div id="url_note"><small><i>'.Mage::helper('megamenu')->__('Blank to use category link').'</i></small></div>',
		));
		
		$fieldset->addField('position', 'text', array(
		  'label'     => Mage::helper('megamenu')->__('Position'),
		  'name'      => 'position',
		  'class'	  => 'validate-number',
		  'required'  => true,
		));
		

		
		$fieldset->addField('columns', 'select', array(
		  'label'     => Mage::helper('megamenu')->__('Columns'),
		  'name'      => 'columns',
		  'values'    => array(
			array(
				'value'=>1,
				'label'=>1
			),
			array(
				'value'=>2,
				'label'=>2
			),
			array(
				'value'=>3,
				'label'=>3
			),
			array(
				'value'=>4,
				'label'=>4
			),
			array(
				'value'=>6,
				'label'=>6
			),
			array(
				'value'=>12,
				'label'=>12
			)
		  ),
		));
		
		$fieldset->addField('special_class', 'text', array(
		  'label'     => Mage::helper('megamenu')->__('Custom Class'),
		  'name'      => 'special_class',
		));
		
		$fieldset->addField('html_label', 'text', array(
		  'label'     => Mage::helper('megamenu')->__('Special Html'),
		  'name'      => 'html_label',
		));


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
		
		if($this->getRequest()->getParam('store')){
			$fieldset->addField('store', 'hidden', array(
			  'name'      => 'store'
			));
		}
		
		
		if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'name' => 'stores[]',
                'label' => Mage::helper('megamenu')->__('Store View'),
                'title' => Mage::helper('megamenu')->__('Store View'),
                'required' => true,
                'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            'style' => 'height:150px',
            ));
        } else {
            $fieldset->addField('store_id', 'hidden', array(
                'name' => 'stores[]',
                'value' => Mage::app()->getStore(true)->getId()
            ));
        }
     
      if ( Mage::getSingleton('adminhtml/session')->getMegamenuData() )
      {
		  $data = Mage::getSingleton('adminhtml/session')->getMegamenuData();
		  
		  if($this->getRequest()->getParam('store')){
			$data['store'] = $this->getRequest()->getParam('store');
		  }
		  
          $form->setValues($data);
          Mage::getSingleton('adminhtml/session')->setMegamenuData(null);
      } elseif ( Mage::registry('megamenu_data') ) {
		  $data = Mage::registry('megamenu_data')->getData();
		  
		  if($this->getRequest()->getParam('store')){
			$data['store'] = $this->getRequest()->getParam('store');
		  }
		  
          $form->setValues($data);
      }
	  
	  
	  
      return parent::_prepareForm();
  }
}