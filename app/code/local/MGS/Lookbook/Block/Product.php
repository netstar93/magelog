<?php
/**
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
 */
class MGS_Lookbook_Block_Product extends MGS_Lookbook_Block_Abstract
{    
	public function getProduct(){
		$product = Mage::getModel('catalog/product')->load($this->getRequest()->getParam('id'));
		return $product;
	}
	
    public function getLookbook()
    {
		$product = $this->getProduct();
		$lookbookId = $product->getMgsLookbook();
		
        $lookbook = Mage::getModel('lookbook/lookbook')
			->getCollection()
			->addFieldToFilter('lookbook_id', $lookbookId)
			->addFieldToFilter('status', 1)
			->getFirstItem();
		
		if($lookbook->getId()){
			return $lookbook;
		}
        return false;
    }
    
}