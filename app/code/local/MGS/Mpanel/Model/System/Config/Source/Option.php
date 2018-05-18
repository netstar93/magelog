<?php
class MGS_Jollyanytheme_Model_System_Config_Source_Option {

    public function toOptionArray() {
		$arrAttribute = array(array('value'=>'','label'=>''));
		
		$attribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', 'product_label');

		if($attribute){
			$options = $attribute->getSource()->getAllOptions(false);
			if(count($options)>0){
				foreach($options as $_option){
					$arrAttribute[] = array(
						'value'=>$_option['value'],
						'label'=>$_option['label']
					);
				}
			}
		}

		return $arrAttribute;
    }

}