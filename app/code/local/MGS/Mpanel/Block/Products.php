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

class MGS_Mpanel_Block_Products extends Mage_Catalog_Block_Product_Abstract
{
    public function getSpecialProducts($type){
		if($featuredAttribute = Mage::getStoreConfig('mpanel/catalog/'.$type)){
		
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
		
			$products = Mage::getModel('catalog/product')
				->getCollection()
				->addStoreFilter(Mage::app()->getStore()->getId())
				->addAttributeToSelect(array('name','small_image', 'final_price', 'price', 'special_price', 'product_label'))
				->addAttributeToFilter($featuredAttribute,1)
				->addAttributeToFilter('status',1)
				->addAttributeToFilter('visibility',array('neq'=>1))
				->setPageSize($limit)
				->setCurPage($curPage);
			$products->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());
			
			
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
				Mage::getSingleton('core/session')->setCountData(array($type=>$count));
			}
			else{
				$countType = Mage::getSingleton('core/session')->getCountData();
				$count = $countType[$type] + count($products);
				Mage::getSingleton('core/session')->setCountData(array($type=>$count));
			}

			if($count <= $this->getCountProduct($featuredAttribute)){
				return $products;
			}
			
			return false;
		}
	}
	
	public function getCountProduct($featuredAttribute){
		
        $products = Mage::getModel('catalog/product')
			->getCollection()
			->addStoreFilter(Mage::app()->getStore()->getId())
			->addAttributeToSelect(array('name','small_image', 'final_price', 'price', 'special_price', 'product_label'))
			->addAttributeToFilter($featuredAttribute,1)
			->addAttributeToFilter('status',1)
			->addAttributeToFilter('visibility',array('neq'=>1));
		$products->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());
			
		if($this->getCategoryId()){
			$category = Mage::getModel('catalog/category')->load($this->getCategoryId());
			if($category->getId()){
				$products->addCategoryFilter($category);
			}else{
				return 0;
			}
		}
		return count($products);
	}
	
	public function getCategoryCollection(){
		$categoryIds = explode(',',$this->getCategoryIds());
		$result = array();
		foreach($categoryIds as $id){
			$category = Mage::getModel('catalog/category')->load($id);
			if($category->getId()){
				$result[] = $category;
			}
		}
		return $result;
	}
	
	public function getProductCategoryByFeatured($category){
		if($category->getId()){
			$productCollection = Mage::getResourceModel('catalog/category')->getProductsPosition($category);
			$productCollection = array_keys($productCollection);
			$collection = Mage::getModel('catalog/product')->getCollection()
				->addStoreFilter(Mage::app()->getStore()->getId())
				->addAttributeToSelect('*')
				->addAttributeToFilter(Mage::getStoreConfig('mpanel/catalog/featured'),1)
				->joinField('position',
					'catalog/category_product',
					'position',
					'product_id=entity_id',
					'category_id='.$category->getId(),
					'left')
				->addFieldToFilter('entity_id', array('in'=>$productCollection));
			
			$collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());
			
			if(count($this->getProductsCount())>0){
				$collection->setPageSize($this->getProductsCount());
			}
			
			return $collection;
		}else{
			return false;
		}
	}
	
	public function getProductCategoryByNew($category){
		if($category->getId()){
			$todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
			$collection = Mage::getResourceModel('catalog/product_collection');
			$collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());

			$collection->addStoreFilter()
				->addAttributeToFilter('news_from_date', array('date' => true, 'to' => $todayDate))
				->addAttributeToFilter('news_to_date', array('or'=> array(
					0 => array('date' => true, 'from' => $todayDate),
					1 => array('is' => new Zend_Db_Expr('null')))
				), 'left')
				->addAttributeToSort('news_from_date', 'desc')
				->setPageSize($this->getProductsCount());
				
			$collection->addCategoryFilter($category);
			return $collection;
		}else{
			return false;
		}
	}
	
	public function getProductCategoryByPosition($category){
		//$productCollection = $category->getProductsPosition();
		if($category->getId()){
			$productCollection = Mage::getResourceModel('catalog/category')->getProductsPosition($category);
			
			$productCollection = array_keys($productCollection);
			$collection = Mage::getModel('catalog/product')->getCollection()
				->addStoreFilter(Mage::app()->getStore()->getId())
				->addAttributeToSelect('*')
				->joinField('position',
					'catalog/category_product',
					'position',
					'product_id=entity_id',
					'category_id='.$category->getId(),
					'left')
				->addFieldToFilter('entity_id', array('in'=>$productCollection));
			
			$collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());
			
			if(count($this->getProductsCount())>0){
				$collection->setPageSize($this->getProductsCount());
			}
			
			return $collection;
		}else{
			return false;
		}
	}
	
	public function getProductByCategory($category){
		if($this->getShowType()){
			$showType = $this->getShowType();
		}
		else{
			$showType = 'featured';
		}
		
		if($showType == 'featured'){
			return $this->getProductCategoryByFeatured($category);
		}
		
		if($showType == 'position'){
			return $this->getProductCategoryByPosition($category);
		}
		
		if($showType == 'new'){
			return $this->getProductCategoryByNew($category);
		}
	}
	
	public function getProductCollection(){
		$todayStartOfDayDate  = Mage::app()->getLocale()->date()
            ->setTime('00:00:00')
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        $todayEndOfDayDate  = Mage::app()->getLocale()->date()
            ->setTime('23:59:59')
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        /** @var $collection Mage_Catalog_Model_Resource_Product_Collection */
        $collection = Mage::getResourceModel('catalog/product_collection');
        $collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());


        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->addAttributeToFilter('news_from_date', array('or'=> array(
                0 => array('date' => true, 'to' => $todayEndOfDayDate),
                1 => array('is' => new Zend_Db_Expr('null')))
            ), 'left')
            ->addAttributeToFilter('news_to_date', array('or'=> array(
                0 => array('date' => true, 'from' => $todayStartOfDayDate),
                1 => array('is' => new Zend_Db_Expr('null')))
            ), 'left')
            ->addAttributeToFilter(
                array(
                    array('attribute' => 'news_from_date', 'is'=>new Zend_Db_Expr('not null')),
                    array('attribute' => 'news_to_date', 'is'=>new Zend_Db_Expr('not null'))
                    )
              )
            ->addAttributeToSort('news_from_date', 'desc')
            ->setPageSize($this->getProductsCount())
            ->setCurPage(1)
        ;
		
		if($this->getCategoryId()){
			$category = Mage::getModel('catalog/category')->load($this->getCategoryId());
			if($category->getId()){
				$collection->addCategoryFilter($category);
			}else{
				return false;
			}
		}

        return $collection;
	}
}
