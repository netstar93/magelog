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
 * @package     Mage_Catalog
 * @copyright   Copyright (c) 2014 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Category controller
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @author     Magento Core Team <core@magentocommerce.com>
 */
require_once('app/code/core/Mage/Catalog/controllers/CategoryController.php');
class MGS_Mpanel_Catalog_CategoryController extends Mage_Catalog_CategoryController
{
    /**
     * Category view action
     */
    public function viewAction()
    {
	
		if($this->getRequest()->isXmlHttpRequest()){
			$response = array();
			
			if ($category = $this->_initCatagory()) {
				$design = Mage::getSingleton('catalog/design');
				$settings = $design->getDesignSettings($category);
				
				// apply custom design
				if ($settings->getCustomDesign()) {
					$design->applyCustomDesign($settings->getCustomDesign());
				}
				
				Mage::getSingleton('catalog/session')->setLastViewedCategoryId($category->getId());
				
				$update = $this->getLayout()->getUpdate();
				$update->addHandle('default');
				
				if (!$category->hasChildren()) {
					$update->addHandle('catalog_category_layered_nochildren');
				}
				
				$this->addActionLayoutHandles();
				$update->addHandle($category->getLayoutUpdateHandle());
				$update->addHandle('CATEGORY_' . $category->getId());
				$this->loadLayoutUpdates();
				
				// apply custom layout update once layout is loaded
				if ($layoutUpdates = $settings->getLayoutUpdates()) {
					if (is_array($layoutUpdates)) {
						foreach($layoutUpdates as $layoutUpdate) {
							$update->addUpdate($layoutUpdate);
						}
					}
				}
				$this->generateLayoutXml()->generateLayoutBlocks(); //Generate new blocks
				$viewpanel = $this->getLayout()->createBlock('catalog/layer_view')->setTemplate('catalog/layer/view.phtml')->toHtml(); // Generate New Layered Navigation Menu
				$productlist = $this->getLayout()->getBlock('product_list')->toHtml(); // Generate product list
				$response['status'] = 'SUCCESS';
				$response['leftcontent']=$viewpanel;
				$response['maincontent'] = $productlist;
			
			// apply custom layout (page) template once the blocks are generated
			}elseif (!$this->getResponse()->isRedirect()) {
				$this->_forward('noRoute');
				$response['status'] = 'FAILURE';			
			}
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
			return;
		}
	
        if ($category = $this->_initCatagory()) {
            $design = Mage::getSingleton('catalog/design');
            $settings = $design->getDesignSettings($category);

            // apply custom design
            if ($settings->getCustomDesign()) {
                $design->applyCustomDesign($settings->getCustomDesign());
            }

            Mage::getSingleton('catalog/session')->setLastViewedCategoryId($category->getId());

            $update = $this->getLayout()->getUpdate();
            $update->addHandle('default');

            if (!$category->hasChildren()) {
                $update->addHandle('catalog_category_layered_nochildren');
            }

            $this->addActionLayoutHandles();
            $update->addHandle($category->getLayoutUpdateHandle());
            $update->addHandle('CATEGORY_' . $category->getId());
            $this->loadLayoutUpdates();

            // apply custom layout update once layout is loaded
            if ($layoutUpdates = $settings->getLayoutUpdates()) {
                if (is_array($layoutUpdates)) {
                    foreach($layoutUpdates as $layoutUpdate) {
                        $update->addUpdate($layoutUpdate);
                    }
                }
            }

            $this->generateLayoutXml()->generateLayoutBlocks();
            // apply custom layout (page) template once the blocks are generated
            if ($settings->getPageLayout()) {
                $this->getLayout()->helper('page/layout')->applyTemplate($settings->getPageLayout());
            }
			else{
				if(Mage::getStoreConfig('mpanel/general/enabled') && (Mage::getStoreConfig('mpanel/catalog/layout')!='')){
					$this->getLayout()->helper('page/layout')->applyTemplate(Mage::getStoreConfig('mpanel/catalog/layout'));
				}
			}

            if ($root = $this->getLayout()->getBlock('root')) {
                $root->addBodyClass('categorypath-' . $category->getUrlPath())
                    ->addBodyClass('category-' . $category->getUrlKey());
            }

            $this->_initLayoutMessages('catalog/session');
            $this->_initLayoutMessages('checkout/session');
            $this->renderLayout();
        }
        elseif (!$this->getResponse()->isRedirect()) {
            $this->_forward('noRoute');
        }
    }
}
