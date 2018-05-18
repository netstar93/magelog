<?php
/**
 * Form tabs class file
 * 
 * @category    MGS
 * @package     MGS_Storelocator
 * @author      MGS Magento Team
 */
class MGS_Storelocator_Block_Adminhtml_Storelocator_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
 /*
  * constructor function
  */
  public function __construct()
  {
      parent::__construct();
      $this->setId('storelocator_tabs');
      $this->setDestElementId('edit_form'); // this should be same as the form id define in form tag class file
      $this->setTitle(Mage::helper('mgs_storelocator')->__('Store Information'));
  }
  
 /**
  * So in the _beforeToHtml() function we specified the actual form field's location. 
  * ie for the first tab, we want to create the file Form.php in the location 
  * Excellence/Employee/Block/Adminhtml/Employee/Edit/Tab and for the second tab 
  * we want to create Image.php in the location Excellence/Employee/Block/Adminhtml/Employee/Edit/Tab. 
  */
  protected function _beforeToHtml()
  {
      /**
       * $this->addTab function used to add as many tabs as you want in your form.
       */
      $this->addTab('form_section_general', array(
          'label'     => Mage::helper('mgs_storelocator')->__('Store Information'),
          'title'     => Mage::helper('mgs_storelocator')->__('Store Information'),
          'content'   => $this->getLayout()->createBlock('mgs_storelocator/adminhtml_storelocator_edit_tab_form')->toHtml(),
      ));
      
      $this->addTab('form_section_google_map', array(
          'label'     => Mage::helper('mgs_storelocator')->__('Store Address'),
          'title'     => Mage::helper('mgs_storelocator')->__('Store Address'),
          'content'   => $this->getLayout()->createBlock('mgs_storelocator/adminhtml_storelocator_edit_tab_googlemap')->toHtml(),
      ));
      
      return parent::_beforeToHtml();
  }
}