<?php
class MGS_Mpanel_Model_Source_Fonts {

	
    public function toOptionArray() {
		
		$fontArray = Mage::helper('mpanel')->getFonts();

		$result = array();
		if(count($fontArray)>0){
			foreach($fontArray as $_font){
				$result[] = array(
					'value' => $_font['css-name'],
					'label' => $_font['font-name'],
				);
			}
		}
		
		return $result;
	}

}