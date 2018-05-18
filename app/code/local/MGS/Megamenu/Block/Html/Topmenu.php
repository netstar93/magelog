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
 * Top menu block
 *
 * @category    Mage
 * @package     Mage_Page
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class MGS_Megamenu_Block_Html_Topmenu extends Mage_Page_Block_Html_Topmenu
{

    /**
     * Recursively generates top menu html from data that is specified in $menuTree
     *
     * @param Varien_Data_Tree_Node $menuTree
     * @param string $childrenWrapClass
     * @return string
     */
    protected function _getHtml(Varien_Data_Tree_Node $menuTree, $childrenWrapClass)
    {
		if(Mage::getStoreConfig('mpanel/general/enabled')){
			$html = '';

			$children = $menuTree->getChildren();
			$parentLevel = $menuTree->getLevel();
			$childLevel = is_null($parentLevel) ? 0 : $parentLevel + 1;

			$counter = 1;
			$childrenCount = $children->count();

			$parentPositionClass = $menuTree->getPositionClass();
			$itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

			foreach ($children as $child) {

				$child->setLevel($childLevel);
				$child->setIsFirst($counter == 1);
				$child->setIsLast($counter == $childrenCount);
				$child->setPositionClass($itemPositionClassPrefix . $counter);

				$outermostClassCode = '';
				$outermostClass = $menuTree->getOutermostClass();

				if ($childLevel == 0 && $outermostClass) {
					$outermostClassCode = ' class="' . $outermostClass . '" ';
					$child->setClass($outermostClass);
				}

				$html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . '>';
				$html .= '<a id="link-mobile-menu-'.$child->getId().'" href="' . $child->getUrl() . '"';
				
				if ($child->hasChildren()) {
					$html .= ' class="dropdown-toggle"';
				}
				
				$html .= '>'.$this->escapeHtml($child->getName());
				
				if ($child->hasChildren()) {
					$html .= ' <i class="fa fa-plus" onclick="mgsjQuery(\'#mobile-menu-'.$child->getId().'\').slideToggle(); mgsjQuery(\'#link-mobile-menu-'.$child->getId().'\').toggleClass(\'collapse\'); mgsjQuery(\'#mobile-menu-'.$child->getId().'\').toggleClass(\'active\'); return false"></i>';
				}

				$html .= '</a>';

				if ($child->hasChildren()) {
					if (!empty($childrenWrapClass)) {
						$html .= '<div class="' . $childrenWrapClass . '">';
					}
					$html .= '<ul class="dropdown-menu" id="mobile-menu-'.$child->getId().'">';
					$html .= $this->_getHtml($child, $childrenWrapClass);
					$html .= '</ul>';

					if (!empty($childrenWrapClass)) {
						$html .= '</div>';
					}
				}
				$html .= '</li>';

				$counter++;
			}

			return $html;
		}
		else{
			$html = '';

			$children = $menuTree->getChildren();
			$parentLevel = $menuTree->getLevel();
			$childLevel = is_null($parentLevel) ? 0 : $parentLevel + 1;

			$counter = 1;
			$childrenCount = $children->count();

			$parentPositionClass = $menuTree->getPositionClass();
			$itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

			foreach ($children as $child) {

				$child->setLevel($childLevel);
				$child->setIsFirst($counter == 1);
				$child->setIsLast($counter == $childrenCount);
				$child->setPositionClass($itemPositionClassPrefix . $counter);

				$outermostClassCode = '';
				$outermostClass = $menuTree->getOutermostClass();

				if ($childLevel == 0 && $outermostClass) {
					$outermostClassCode = ' class="' . $outermostClass . '" ';
					$child->setClass($outermostClass);
				}

				$html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . '>';
				$html .= '<a href="' . $child->getUrl() . '" ' . $outermostClassCode . '><span>'
					. $this->escapeHtml($child->getName()) . '</span></a>';

				if ($child->hasChildren()) {
					if (!empty($childrenWrapClass)) {
						$html .= '<div class="' . $childrenWrapClass . '">';
					}
					$html .= '<ul class="level' . $childLevel . '">';
					$html .= $this->_getHtml($child, $childrenWrapClass);
					$html .= '</ul>';

					if (!empty($childrenWrapClass)) {
						$html .= '</div>';
					}
				}
				$html .= '</li>';

				$counter++;
			}

			return $html;
		}
    }

    /**
     * Returns array of menu item's classes
     *
     * @param Varien_Data_Tree_Node $item
     * @return array
     */
    protected function _getMenuItemClasses(Varien_Data_Tree_Node $item)
    {
		if(Mage::getStoreConfig('mpanel/general/enabled')){
			$classes = array();

			$classes[] = 'level' . $item->getLevel();
			$classes[] = $item->getPositionClass();

			if ($item->getIsFirst()) {
				$classes[] = 'first';
			}

			if ($item->getIsActive()) {
				$classes[] = 'active';
			}

			if ($item->getIsLast()) {
				$classes[] = 'last';
			}

			if ($item->getClass()) {
				$classes[] = $item->getClass();
			}

			if ($item->hasChildren()) {
				if($item->getLevel()==0){
					$classes[] = 'dropdown';
				}
				else{
					$classes[] = 'dropdown-submenu';
				}
			}

			return $classes;
		}
		else{
			$classes = array();

			if ($item->getIsFirst()) {
				$classes[] = '';
			}

			if ($item->getIsActive()) {
				$classes[] = 'active';
			}

			if ($item->getIsLast()) {
				$classes[] = '';
			}

			if ($item->getClass()) {
				$classes[] = $item->getClass();
			}

			if ($item->hasChildren()) {
				$classes[] = 'dropdown';
			}

			return $classes;
		}
    }
}
