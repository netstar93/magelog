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
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2014 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class MGS_Mpanel_Block_Product_Rate extends Mage_Catalog_Block_Product_Abstract
{
    public function getTopRateProducts(){
		if($this->getRequest()->getParam('p')){
			$curPage = $this->getRequest()->getParam('p');
		}else{
			$curPage = 1;
		}
		
		if($this->getRequest()->getParam('limit')){
			$limit = $this->getRequest()->getParam('limit');
		}else{
			$limit = $this->getProductsCount();
		}
		
		$products = Mage::getResourceModel('reports/product_collection')
			->addStoreFilter(Mage::app()->getStore()->getId())
		    ->addAttributeToSelect(array('name','small_image', 'final_price', 'price', 'special_price', 'product_label'))
			->addAttributeToFilter('status',1)
			->addAttributeToFilter('visibility',array('neq'=>1))
			->setPageSize($limit)
			->setCurPage($curPage);

		$products->joinField('rating_summary', 'review/review_aggregate', 'rating_summary', 'entity_pk_value=entity_id',  array('entity_type' => 1, 'store_id' => Mage::app()->getStore()->getId()), 'left');
		
		$products->joinField('reviews_count', 'review/review_aggregate', 'reviews_count', 'entity_pk_value=entity_id',  array('entity_type' => 1, 'store_id' => Mage::app()->getStore()->getId()), 'left');

		$products->setOrder('rating_summary', 'desc');
		$products->setOrder('reviews_count', 'desc');
			
		if($this->getCategoryId()){
			$category = Mage::getModel('catalog/category')->load($this->getCategoryId());
			
			if($category->getId()){
				$products->addCategoryFilter($category);
			}else{
				return false;
				
			}
			
		}
		
		if($curPage==1){
			$count = count($products);
			Mage::getSingleton('core/session')->setCountData(array('rate'=>$count));
		}
		else{
			$countType = Mage::getSingleton('core/session')->getCountData();
			$count = $countType['rate'] + count($products);
			Mage::getSingleton('core/session')->setCountData(array('rate'=>$count));
		}

		if($count <= $this->getCountProduct()){
			return $products;
		}
		
		return false;
	}
	
	public function getCountProduct(){
		$products = Mage::getResourceModel('reports/product_collection')
			->addStoreFilter(Mage::app()->getStore()->getId())
		    ->addAttributeToSelect(array('name','small_image', 'final_price', 'price', 'special_price', 'product_label'))
			->addAttributeToFilter('status',1)
			->addAttributeToFilter('visibility',array('neq'=>1));

		$products->joinField('rating_summary', 'review/review_aggregate', 'rating_summary', 'entity_pk_value=entity_id',  array('entity_type' => 1, 'store_id' => Mage::app()->getStore()->getId()), 'left');
		
		$products->joinField('reviews_count', 'review/review_aggregate', 'reviews_count', 'entity_pk_value=entity_id',  array('entity_type' => 1, 'store_id' => Mage::app()->getStore()->getId()), 'left');

		$products->setOrder('rating_summary', 'desc');
		$products->setOrder('reviews_count', 'desc');
		
		return count($products);
	}
}