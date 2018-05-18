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

class MGS_Mpanel_Block_Product_Sale extends Mage_Catalog_Block_Product_Abstract
{
    public function getSaleProducts(){
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
		
		$todayDate = Mage::getModel('core/date')->date('m/d/y');
		$tomorrow = mktime(0, 0, 0, date('m'), date('d'), date('y'));
		$tomorrowDate = date('m/d/y', $tomorrow); 
		
		if($this->getRequest()->getParam('p')){
			$curPage = $this->getRequest()->getParam('p');
			$products = Mage::getModel('catalog/product')
					->getCollection()
					->addStoreFilter(Mage::app()->getStore()->getId())
					->addAttributeToSelect(array('name','small_image', 'final_price', 'price', 'special_price', 'product_label'))
					->addAttributeToFilter('status',1)
					->addAttributeToFilter('visibility',array('neq'=>1))
					->addFinalPrice()
					->addAttributeToFilter('special_from_date', array('date' => true, 'to' => $todayDate))
					->addAttributeToFilter('special_to_date', array('or'=> array(
						0 => array('date' => true, 'from' => $tomorrowDate),
						1 => array('is' => new Zend_Db_Expr('null')))
						), 'left');
			
			$numberPage = ceil(count($products) / $limit);
			
			if($curPage <= $numberPage){
				$products = Mage::getModel('catalog/product')
					->getCollection()
					->addStoreFilter(Mage::app()->getStore()->getId())
					->addAttributeToSelect(array('name','small_image', 'final_price', 'price', 'special_price', 'product_label'))
					->addAttributeToFilter('status',1)
					->addAttributeToFilter('visibility',array('neq'=>1))
					->addFinalPrice()
					->setPageSize($limit)
					->setCurPage($curPage)
					->addAttributeToFilter('special_from_date', array('date' => true, 'to' => $todayDate))
					->addAttributeToFilter('special_to_date', array('or'=> array(
						0 => array('date' => true, 'from' => $tomorrowDate),
						1 => array('is' => new Zend_Db_Expr('null')))
						), 'left');
			}else {
				return false;
			}
		}else {
			$curPage = 1;
			$products = Mage::getModel('catalog/product')
				->getCollection()
				->addStoreFilter(Mage::app()->getStore()->getId())
				->addAttributeToSelect(array('name','small_image', 'final_price', 'price', 'special_price', 'product_label'))
				->addAttributeToFilter('status',1)
				->addAttributeToFilter('visibility',array('neq'=>1))
				->addFinalPrice()
				->setPageSize($limit)
				->setCurPage($curPage)
				->addAttributeToFilter('special_from_date', array('date' => true, 'to' => $todayDate))
				->addAttributeToFilter('special_to_date', array('or'=> array(
					0 => array('date' => true, 'from' => $tomorrowDate),
					1 => array('is' => new Zend_Db_Expr('null')))
					), 'left');
		}
		
		$products->getSelect()->where('price_index.final_price < price_index.price');
			
		if($this->getCategoryId()){
			$category = Mage::getModel('catalog/category')->load($this->getCategoryId());
			if($category->getId()){
				$products->addCategoryFilter($category);
			}else{
				return false;
				
			}
		}
		/* echo $products->getSelect();
		die(); */
		
		if($curPage==1){
			$count = count($products);
			Mage::getSingleton('core/session')->setCountData(array('sale'=>$count));
		}
		else{
			$countType = Mage::getSingleton('core/session')->getCountData();
			$count = $countType['sale'] + count($products);
			Mage::getSingleton('core/session')->setCountData(array('sale'=>$count));
		}

		if($count <= $this->getCountProduct()){
			return $products;
		}
		
		return false;
	}
	
	public function getCountProduct(){
		$todayDate = Mage::getModel('core/date')->date('m/d/y');
		$tomorrow = mktime(0, 0, 0, date('m'), date('d'), date('y'));
		$tomorrowDate = date('m/d/y', $tomorrow); 
		
        $products = Mage::getModel('catalog/product')
			->getCollection()
			->addStoreFilter(Mage::app()->getStore()->getId())
			->addAttributeToSelect(array('name','small_image', 'final_price', 'price', 'special_price', 'product_label'))
			->addAttributeToFilter('status',1)
			->addAttributeToFilter('visibility',array('neq'=>1))
			->addFinalPrice()
			->addAttributeToFilter('special_from_date', array('date' => true, 'to' => $todayDate))
			->addAttributeToFilter('special_to_date', array('or'=> array(
				0 => array('date' => true, 'from' => $tomorrowDate),
				1 => array('is' => new Zend_Db_Expr('null')))
				), 'left');
		
		$products->getSelect()->where('price_index.final_price < price_index.price');
		
		if($this->getCategoryId()){
			$category = Mage::getModel('catalog/category')->load($this->getCategoryId());

			if($category->getId()){
				$products->addCategoryFilter($category);
			}else{
				return false;
				
			}
		}
		
		return count($products);
	}
}