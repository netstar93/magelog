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
class MGS_Megamenu_Block_Adminhtml_Megamenu_Edit_Tab_Category extends Mage_Adminhtml_Block_Widget
{
	protected $_ids;
	
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('mgs_megamenu/category.phtml');
    }
	
	public function isCategoryActive($category)
    {
        if ($this->getRequest()->getParam('id')) {
			if(Mage::getModel('megamenu/megamenu')->load($this->getRequest()->getParam('id'))->getCategoryId()!=0){
				$categoryId = Mage::getModel('megamenu/megamenu')->load($this->getRequest()->getParam('id'))->getCategoryId();
				$currentCategory = Mage::getModel('catalog/category')->load($categoryId);
				return in_array($category->getId(), $currentCategory->getPathIds());
			}
			return false;
        }
        return false;
    }

    public function getCategoryCollection()
    {
        $collection = $this->getData('category_collection');
        if (is_null($collection)) {
            $collection = Mage::getModel('catalog/category')->getCollection();

            /* @var $collection Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection */
            $collection->addAttributeToSelect('name');
        }
        return $collection;
    }
	
	public function getTreeCategory($category, $parent, $ids = array()){
		if ($this->getRequest()->getParam('id')) {
			if(Mage::getModel('megamenu/megamenu')->load($this->getRequest()->getParam('id'))->getCategoryId()!=0){
				$categoryId = Mage::getModel('megamenu/megamenu')->load($this->getRequest()->getParam('id'))->getCategoryId();
			}
			else{
				$categoryId = 0;
			}
        }
		else{
			$categoryId = 0;
		}
		
		$children = $category->getChildrenCategories();
		$childrenCount = count($children);
		
		$htmlLi = '<li>';
		$html[] = $htmlLi;
		if($this->isCategoryActive($category)){
			$ids[] = $category->getId();
			$this->_ids = implode(",", $ids);
		}
		
		$html[] = '<a id="node'.$category->getId().'">';

		$html[] = '<input lang="'.$category->getId().'" onclick="setCheckbox('.$category->getId().')" type="radio" id="radio'.$category->getId().'" name="category_id" value="'.$category->getId().'" class="radio checkbox'.$parent.'"';
		if($categoryId == $category->getId()){
			$html[] = ' checked="checked"';
		}
		$html[] = '/><label for="radio'.$category->getId().'">' . $category->getName() . '</label>';

		$html[] = '</a>';
		
		$htmlChildren = '';
		if($childrenCount>0){
			foreach ($children as $child) {

				$_child = Mage::getModel('catalog/category')->load($child->getId());
				$htmlChildren .= $this->getTreeCategory($_child, $category->getId(), $ids);
			}
		}
		if (!empty($htmlChildren)) {
            $html[] = '<ul id="container'.$category->getId().'">';
            $html[] = $htmlChildren;
            $html[] = '</ul>';
        }

        $html[] = '</li>';
        $html = implode("\n", $html);
        return $html;
	}
    
}
