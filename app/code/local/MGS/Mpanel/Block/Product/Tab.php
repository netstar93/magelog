<?php

class MGS_Mpanel_Block_Product_Tab extends Mage_Core_Block_Template {

    public function getAttributes() {
        $result = array();
        $attributes = Mage::getResourceModel('catalog/product_attribute_collection')
                ->addFieldToFilter('frontend_input', array(
                    array('eq' => 'text'),
                    array('eq' => 'textarea'),
                ))
                ->addFieldToFilter('frontend_label', array('neq' => NULL))
                ->setOrder('frontend_label', 'asc')
                ->getItems();
        foreach ($attributes as $attribute) {
            $result[] = array('label' => $attribute->getFrontendLabel(), 'value' => $attribute->getAttributeCode());
        }
        return $result;
    }

    public function getStaticBlocks($storeId) {
        $result = array();
        $tableName = Mage::getSingleton('core/resource')->getTableName('cms/block_store');
		
		
		$childCollection =Mage::getModel('mpanel/childs')->getCollection()
			->addFieldToSelect('static_block_id');
		$childCollection->getSelect()->distinct(true);
		
		$arrExist = array();
		if(count($childCollection)>0){
			foreach($childCollection as $_child){
				$arrExist[] = $_child->getStaticBlockId();
			}
		}

        $collection = Mage::getModel('cms/block')->getCollection()
                ->addFieldToFilter('is_active', array('eq' => 1))
                ->setOrder('title', 'asc');
        $collection->getSelect()->join(array('cms_block_store' => $tableName), 'main_table.block_id = cms_block_store.block_id', array('cms_block_store.store_id'));
        $collection->getSelect()->where('cms_block_store.store_id = ? OR cms_block_store.store_id = 0', $storeId)->where('main_table.block_id NOT IN (?)', $arrExist);
        foreach ($collection as $block) {
            $result[] = array('label' => $block->getTitle(), 'value' => $block->getIdentifier());
        }
        return $result;
    }

}
