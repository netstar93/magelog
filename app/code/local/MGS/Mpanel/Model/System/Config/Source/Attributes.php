<?php
class MGS_Jollyanytheme_Model_System_Config_Source_Attributes {

    public function toOptionArray() {
		$productAttrs = Mage::getResourceModel('catalog/product_attribute_collection')
			->addFieldToFilter('frontend_label', array('notnull'=>true));
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