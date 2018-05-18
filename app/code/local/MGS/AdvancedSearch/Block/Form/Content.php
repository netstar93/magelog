<?php

/* * ****************************************************
 * Package   : AdvancedSearch
 * Author    : HIEPNH
 * Copyright : (c) 2014
 * ***************************************************** */
?>
<?php

class MGS_AdvancedSearch_Block_Form_Content extends Mage_Core_Block_Template {

    protected function _construct() {
        $this->addData(array(
            'cache_lifetime' => false,
            'cache_tags' => array(Mage_Core_Model_Store::CACHE_TAG, Mage_Cms_Model_Block::CACHE_TAG)
        ));
    }

    public function getSearchableCategories() {
        $rootCategory = Mage::getModel('catalog/category')->load(Mage::app()->getStore()->getRootCategoryId());
        return $this->getSearchableSubCategories($rootCategory);
    }

    public function getSearchableSubCategories($category) {
        return Mage::getModel('catalog/category')->getCollection()
                        ->addAttributeToSelect('name')
                        ->addAttributeToSelect('all_children')
                        ->addAttributeToFilter('is_active', 1)
                        ->addAttributeToFilter('include_in_menu', 1)
                        ->addIdFilter($category->getChildren())
                        ->setOrder('position', 'ASC')
                        ->load();
    }

}
