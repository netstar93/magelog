<?php
class MGS_Mpanel_Model_Source_Brands {

    public function toOptionArray() {
		$arrAttribute = array(array('value'=>'','label'=>''));
		$productAttrs = Mage::getResourceModel('catalog/product_attribute_collection')
			->addFieldToFilter('backend_type', 'int')
			->addFieldToFilter('frontend_input', 'select')
			//->addFieldToFilter('source_model', 'eav/entity_attribute_source_table')
			;
		if(count($productAttrs)>0){
			foreach ($productAttrs as $productAttr) { 
				$arrAttribute[] = array(
					'value'=>$productAttr->getAttributeCode(),
					'label'=>$productAttr->getFrontendLabel()
				);
			}
		}
		return $arrAttribute;
    }

}