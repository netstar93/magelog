<?php
/**
 * Google map tab class file
 * 
 * @category    MGS
 * @package     MGS_Storelocator
 * @author      MGS Magento Team
 */

class MGS_Storelocator_Block_Adminhtml_Storelocator_Edit_Tab_Googlemap extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model = Mage::registry('storelocator_data');
        
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('form_General_Googlemap', array('legend'=>Mage::helper('mgs_storelocator')->__('Store Address')));
		
		
		$fieldset->addField('street_address', 'text', array(
          'label'     => Mage::helper('mgs_storelocator')->__('Street Address'),
          'name'      => 'street_address',
		  'required'  => true,
        ));
		
		$country = Mage::getResourceModel('directory/country_collection');
		//$countryArray = array('value'=>'','label'=>'');
		foreach($country as $_country){
			$countryArray[] = array('value'=>$_country->getName(), 'label'=>$_country->getName());
		}
		array_unshift($countryArray, array('value'=>'', 'label'=> Mage::helper('adminhtml')->__('--Please Select--')));
		
        
        $fieldset->addField('country', 'select', array(
            'name'  => 'country',
            'class'     => 'required-select',
            'required'  => true,
            'label'     => 'Country',
            'values'    => $countryArray,
        ));
        
        $fieldset->addField('state', 'text', array(
          'label'     => Mage::helper('mgs_storelocator')->__('State'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'state',
        ));
        
        $fieldset->addField('city', 'text', array(
          'label'     => Mage::helper('mgs_storelocator')->__('City'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'city',
        ));
        
        $fieldset->addField('zipcode', 'text', array(
          'label'     => Mage::helper('mgs_storelocator')->__('Zipcode'),
          'required'  => true,
          'name'      => 'zipcode',
        ));
		
		$fieldset->addField('button', 'note', array(
          'label'     => '',
		  'after_element_html' => '<button onclick="getMapbyAddress()" type="button"><span><span><span>'.Mage::helper('mgs_storelocator')->__('Get Map').'</span></span></span></button>',
        ));
		
		
		$locationFieldset = $form->addFieldset('form_location', array('legend'=>Mage::helper('mgs_storelocator')->__('Store Location')));

        
        $locationFieldset->addField('latitude', 'text', array(
          'label'     => Mage::helper('mgs_storelocator')->__('Latitude'),
          'class'     => 'validate-number',
          'required'  => true,
          'name'      => 'latitude',
        ));
        
        $locationFieldset->addField('longitude', 'text', array(
          'label'     => Mage::helper('mgs_storelocator')->__('Longitude'),
          'class'     => 'validate-number',
          'required'  => true,
          'name'      => 'longitude',
        ));
		
		$locationFieldset->addField('button2', 'note', array(
          'label'     => '',
		  'after_element_html' => '<button onclick="getMapByLocation()" type="button"><span><span><span>'.Mage::helper('mgs_storelocator')->__('Get Map').'</span></span></span></button>',
        ));
		
		$mapFieldset = $form->addFieldset('form_map', array('legend'=>''));
		
		//$mapFieldset->setElementRenderer(new MGS_Storelocator_Block_Adminhtml_Storelocator_Renderer_Map);
		//$mapFieldset->setHeaderBar('<button type="button" onclick="alert(\'click me!!\')">click</button>');
		
        $data = $model->getData();
        if(!empty($data)) {
            $form->setValues($data);
        }
       return parent::_prepareForm();
    }
}