<?php

class MGS_Deals_Helper_Data extends MGS_Mgscore_Helper_Data
{
	public function changeStatus($dealId, $status){
		try {
			Mage::getModel('deals/deals')->setStatus($status)->setId($dealId)->save();
		} catch (Exception $e) {
			
		}
	}
	
	public function changeStatusOfCollection(){
		$dealCollection = Mage::getModel('deals/deals')
			->getCollection()
			->addFieldToFilter('status', array('neq' => 3));
		if(count($dealCollection)>0){
			foreach($dealCollection as $deal){
				$_product = Mage::getModel('catalog/product')->load($deal->getProductId());
				$timezone = Mage::app()->getStore()->getConfig('general/locale/timezone');
				$currentTimezone = @date_default_timezone_get();
				@date_default_timezone_set($timezone);
				$startTime = $deal->getStartTime();
				$now = date('Y-m-d H:i:s');
				$endTime = str_replace('00:00:00','23:59:59',$deal->getEndTime());
				
				if(($_product->getSpecialPrice()!='') 
				&& ($_product->getSpecialPrice()<$_product->getPrice())
				&& ($startTime!='') && ($endTime!='')
				){
					$status = 0;
					if($startTime>$now && $endTime>$now && $endTime>$startTime){
						$status = 1;
					}
					if($now>$startTime && $now<$endTime){
						$status = 2;
					}
					if($startTime<$now && $endTime<$now && $endTime>$startTime){
						$status = 3;
					}
					if($status>0){
						$this->changeStatus($deal->getId(), $status);
					}
				}
				else{
					$this->changeStatus($deal->getId(), 3);
				}
				
				// Done Deal if Deal Qty = 0
				if(($deal->getMaxDealQty()!='') && ($deal->getMaxDealQty()<1)){
					$this->changeStatus($deal->getId(), 3);
				}
			}
		}
	}
	
	public function updateProduct($productId){
		$_product = Mage::getModel('catalog/product')->load($productId);
		$_product->setSpecialPrice(NULL)
			->setSpecialFromDate(NULL)
			->setSpecialToDate(NULL)
			->setAhtDealQty(NULL)
			->save();
	}
	
	public function updateDealQty($productId, $sold){
		$_product = Mage::getModel('catalog/product')->load($productId);
		$ahtDealQty = $_product->getAhtDealQty();
		$ahtDealQty = $ahtDealQty - $sold;
		
		$_product->setAhtDealQty($ahtDealQty)
			->save();
	}
}