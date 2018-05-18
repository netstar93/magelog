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
 * @package     Mage_Page
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Html page block
 *
 * @category   Mage
 * @package    Mage_Page
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class MGS_Mpanel_Block_Page_Html_Footer extends Mage_Page_Block_Html_Footer
{
    public function _construct()
    {
		$footer = Mage::getStoreConfig('mgs_theme/general/footer');
		if($footer!='' && Mage::getStoreConfig('mpanel/general/enabled')){
			$helper = Mage::helper('mpanel');
			if($helper->acceptToUsePanel()){
				$this->setTemplate('page/html/footer/admin/'.$footer.'.phtml');
			}
			else{
				$this->setTemplate('page/html/footer/'.$footer.'.phtml');
			}
		}
		else{
			parent::_construct();
		}
    }


}
