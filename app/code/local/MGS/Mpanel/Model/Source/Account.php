<?php
class MGS_Mpanel_Model_Source_Account {

	
    public function toOptionArray() {
	
		$users = Mage::getModel('customer/customer')
			->getCollection()
			->setOrder('entity_id', 'DESC');
		
		$arrCustomer = array();
		
		if(count($users)>0){
			foreach($users as $customer){
				$arrCustomer[] = array(
					'value'	=> $customer->getId(),
					'label'	=> $customer->getEmail()
				);
			}
		}
	
        return $arrCustomer;
    }

}