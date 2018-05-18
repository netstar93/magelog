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
 * @package     Mage_Cms
 * @copyright   Copyright (c) 2014 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Cms block content block
 *
 * @category   Mage
 * @package    Mage_Cms
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class MGS_Mpanel_Block_Cms_Block extends Mage_Cms_Block_Block
{
    /**
     * Prepare Content HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
		$blockId = $this->getBlockId();
        $html = '';
		$panelHelper = $this->helper('mpanel');
        
        if ($blockId) {
            $block = Mage::getModel('cms/block')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($blockId);
            if ($block->getIsActive()) {
				if($panelHelper->acceptToUsePanel() && $this->getCanEdit()){
					$html .= '<div class="builder-container child-builder static-can-edit"><div class="edit-panel child-panel"><ul><li><a title="'.$this->__('Edit').'" class="popup-link" href="'.$this->getUrl('mpanel/edit/static',array('id'=>$block->getIdentifier())).'"><em class="fa fa-edit">&nbsp;</em></a></li></ul></div><div id="static_'.$block->getIdentifier().'">';
				}
				
				/* @var $helper Mage_Cms_Helper_Data */
                $helper = Mage::helper('cms');
                $processor = $helper->getBlockTemplateProcessor();
                $html .= $processor->filter($block->getContent());
				if(version_compare('1.7.1', Mage::getVersion()) == -1){
					$this->addModelTags($block);
				}
				
				if($panelHelper->acceptToUsePanel() && $this->getCanEdit()){
					$html .= '</div></div>';
				}
            }
        }
		
        return $html;
    }
}
