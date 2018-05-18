<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Eav
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Attribute add/edit form options tab
 *
 * @category   Mage
 * @package    Mage_Eav
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class MGS_Megamenu_Block_Adminhtml_Megamenu_Edit_Tab_Topbottom extends Mage_Adminhtml_Block_Widget_Form
{

    /* public function __construct()
    {
        parent::__construct();
        $this->setTemplate('mgs_megamenu/topbottom.phtml');
    } */

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('topbottom_form', array('legend' => Mage::helper('megamenu')->__('Top & Bottom Content')));



        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
			
		);
		//$wysiwygConfig['add_widgets'] = false;
		//$wysiwygConfig['add_variables'] = false;
		$wysiwygConfig["files_browser_window_url"] = Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index');
		$wysiwygConfig["directives_url"] = Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive');
		$wysiwygConfig["directives_url_quoted"] = Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive');
		$wysiwygConfig["widget_window_url"] = Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/widget/index');
		$wysiwygConfig["files_browser_window_width"] = (int) Mage::getConfig()->getNode('adminhtml/cms/browser/window_width');
		$wysiwygConfig["files_browser_window_height"] = (int) Mage::getConfig()->getNode('adminhtml/cms/browser/window_height');
		$plugins = $wysiwygConfig->getData("plugins");
		$plugins[0]["options"]["url"] = Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/system_variable/wysiwygPlugin');
		$plugins[0]["options"]["onclick"]["subject"] = "MagentovariablePlugin.loadChooser('".Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/system_variable/wysiwygPlugin')."', '{{html_id}}');";

		$plugins = $wysiwygConfig->setData("plugins",$plugins);

        $fieldset->addField('top_content', 'editor', array(
            'name' => 'top_content',
            'label' => Mage::helper('megamenu')->__('Top Content'),
            'title' => Mage::helper('megamenu')->__('Top Content'),
            'style' => 'height:10em; width:700px',
            'config' => $wysiwygConfig,
            'required' => false,
        ));
		
        $fieldset->addField('bottom_content', 'editor', array(
            'name' => 'bottom_content',
            'label' => Mage::helper('megamenu')->__('Bottom Content'),
            'title' => Mage::helper('megamenu')->__('Bottom Content'),
            'style' => 'height:10em; width:700px',
            'config' => $wysiwygConfig,
            'required' => false,
        ));
		
		/* $fieldset->addField('left_content', 'editor', array(
            'name' => 'left_content',
            'label' => Mage::helper('megamenu')->__('Left Content'),
            'title' => Mage::helper('megamenu')->__('Left Content'),
            'style' => 'height:10em; width:700px',
            'config' => $wysiwygConfig,
            'required' => false,
        ));
		
		$fieldset->addField('right_content', 'editor', array(
            'name' => 'right_content',
            'label' => Mage::helper('megamenu')->__('Right Content'),
            'title' => Mage::helper('megamenu')->__('Right Content'),
            'style' => 'height:10em; width:700px',
            'config' => $wysiwygConfig,
            'required' => false,
        )); */


        

		if ( Mage::getSingleton('adminhtml/session')->getMegamenuData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getMegamenuData());
          Mage::getSingleton('adminhtml/session')->setMegamenuData(null);
      } elseif ( Mage::registry('megamenu_data') ) {
          $form->setValues(Mage::registry('megamenu_data')->getData());
      }
      return parent::_prepareForm();
    }
    
}
