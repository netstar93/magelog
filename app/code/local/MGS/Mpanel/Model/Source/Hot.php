<?php
class MGS_Mpanel_Model_Source_Hot {

    public function toOptionArray() {
		$arrAttribute = array(array('value'=>'','label'=>''));
		$productAttrs = Mage::getResourceModel('catalog/product_attribute_collection')
			->addFieldToFilter('backend_type', 'int')
			->addFieldToFilter('frontend_input', 'boolean')
			->addFieldToFilter('source_model', 'eav/entity_attribute_source_boolean');
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