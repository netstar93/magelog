<?php
class MGS_Deals_Model_Observer
{
	// Save deal after product save
    public function saveDeals(Varien_Event_Observer $observer)
    {
		if(Mage::getStoreConfig('deals/general/enabled')){
			$dealModel = Mage::getModel('deals/deals');
			$data = array();
			
			$product = $observer->getEvent()->getProduct();
			
			// Just update if product type is simple or configurable
			if($product->getTypeID() == 'simple' || $product->getTypeID() == 'configurable' || $product->getTypeID() == 'downloadable'){
				$data['product_id'] = $productId = $product->getId();
				$timezone = Mage::app()->getStore()->getConfig('general/locale/timezone');
				$currentTimezone = @date_default_timezone_get();
				@date_default_timezone_set($timezone);
				$data['start_time'] = $startTime = $product->getSpecialFromDate();
				$now = date('Y-m-d H:i:s');
				$data['end_time'] = $endTime = str_replace('00:00:00','23:59:59',$product->getSpecialToDate());
				
				$deal = Mage::getModel('deals/deals')
					->getCollection()
					->addFieldToFilter('product_id', $productId)
					->addFieldToFilter('status', array('neq' => 3))
					->getFirstItem();
				
				if(($product->getSpecialPrice()!='') 
				&& ($product->getSpecialPrice()<$product->getPrice())
				&& ($startTime!='') && ($endTime!='')
				){
					
					$data['product_name'] = $product->getName();
					$data['price'] = $product->getPrice();
					$data['special_price'] = $product->getSpecialPrice();
					if($product->getTypeID() == 'configurable'){
						$data['max_deal_qty'] = $data['deal_qty'] = NULL;
					}
					else{
						$data['max_deal_qty'] = $data['deal_qty'] = $product->getAhtDealQty();
					}
					$data['qty'] = $qtyStock = round(Mage::getModel('cataloginventory/stock_item')->loadByProduct($product)->getQty());
					
					// Status is Processing
					if($startTime>$now && $endTime>$now && $endTime>$startTime){
						$data['status'] = 1;
					}
					
					// Status is Running
					if($now>$startTime && $now<$endTime){
						$data['status'] = 2;
					}
					
					// Status is Done
					if($startTime<$now && $endTime<$now && $endTime>$startTime){
						$data['status'] = 3;
					}
					
					if(isset($data['status'])){
						$dealModel->setData($data);
							
						if($deal->getId()){
							$dealModel->setId($deal->getId());
						}
						
						try {
							// Save deal
							$dealModel->save();
						} catch (Exception $e) {
							Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
						}
					}
				}
				else{
					if($deal->getId()){
						Mage::helper('deals/data')->changeStatus($deal->getId(), 3);
					}
				}
			}
			if(Mage::app()->getRequest()->getParam('deal')){
				Mage::getSingleton('adminhtml/session')->setRedirectDeal(1);
			}
			else{
				Mage::getSingleton('adminhtml/session')->setRedirectDeal(false);
			}
		}
	}
	
	// Update deal quantity when order completed
	public function updateQty(Varien_Event_Observer $observer){
		if(Mage::getStoreConfig('deals/general/enabled')){
			if(Mage::getStoreConfig('deals/general/update')=='order'){
				$order = $observer->getOrder();
				$status = $observer->getStatus();
				
				$items = $order->getAllItems();
				$ids = $qty = array();
				foreach ($items as $itemId => $item)
				{
					$ids[]=$item->getProductId();
					$qty[]=$item->getQtyOrdered();
				}
				
				// Update deal if order status is complete
				if($status == 'complete'){
					if(count($ids)>0){
						foreach($ids as $key=>$productId){
							$product = Mage::getModel('catalog/product')->load($productId);
							$deal = Mage::getModel('deals/deals')
								->getCollection()
								->addFieldToFilter('product_id', $productId)
								->addFieldToFilter('status', 2)
								->getFirstItem();
								
							if($deal->getId()){
								$dealModel = Mage::getModel('deals/deals');
								$dealQty = $oldDealQty = $deal->getMaxDealQty();
								$sold = $deal->getSold();
								$sold = $sold + $qty[$key];
								$dealModel->setSold($sold);
								
								if($dealQty==''){
									$dealModel->setMaxDealQty(NULL);
								}
								else{
									if($dealQty>0){
										$dealQty = $deal->getDealQty() - $sold;
										$dealModel->setMaxDealQty($dealQty);
										//Mage::helper('deals/data')->updateDealQty($productId, $sold);
									}
									if($dealQty<1){
										Mage::helper('deals/data')->updateProduct($productId);
										$dealModel->setStatus(3);
									}
								}
								$dealModel->setId($deal->getId())->save();
							}
						}
					}
				}
			}
		}
	}
	
	public function updateDeals(Varien_Event_Observer $observer){
		if(Mage::getStoreConfig('deals/general/enabled')){
			if(Mage::getStoreConfig('deals/general/update')=='checkout'){
				$order = $observer->getOrder();
				$items = $order->getAllItems();
				$ids = $qty = array();
				foreach ($items as $itemId => $item)
				{
					$ids[]=$item->getProductId();
					$qty[]=$item->getQtyOrdered();
				}
				if(count($ids)>0){
					foreach($ids as $key=>$productId){
						$product = Mage::getModel('catalog/product')->load($productId);
						$deal = Mage::getModel('deals/deals')
							->getCollection()
							->addFieldToFilter('product_id', $productId)
							->addFieldToFilter('status', 2)
							->getFirstItem();
							
						if($deal->getId()){
							$dealModel = Mage::getModel('deals/deals');
							$dealQty = $oldDealQty = $deal->getMaxDealQty();
							$sold = $deal->getSold();
							$sold = $sold + $qty[$key];
							$dealModel->setSold($sold);
							
							if($dealQty==''){
								$dealModel->setMaxDealQty(NULL);
							}
							else{
								if($dealQty>0){
									$dealQty = $deal->getDealQty() - $sold;
									$dealModel->setMaxDealQty($dealQty);
									//Mage::helper('deals/data')->updateDealQty($productId, $sold);
								}
								if($dealQty<1){
									Mage::helper('deals/data')->updateProduct($productId);
									$dealModel->setStatus(3);
								}
							}
							$dealModel->setId($deal->getId())->save();
						}
					}
				}
			}
		}
	}
	
	public function modifyPrice(Varien_Event_Observer $observer){
		if(Mage::getStoreConfig('deals/general/enabled')){
			$item = $observer->getQuoteItem();
			$item = ( $item->getParentItem() ? $item->getParentItem() : $item );
			
			$productId = $item->getProductId();
			$qty = $item->getQty();
			
			$deal = Mage::getModel('deals/deals')
				->getCollection()
				->addFieldToFilter('product_id', $productId)
				->addFieldToFilter('status', 2)
				->getFirstItem();
				
			if($deal->getId()){
				$dealQty = $deal->getMaxDealQty();
				if($dealQty!=''){
					if($qty>$dealQty){
						$price = $deal->getPrice();
						$specialPrice =  $deal->getSpecialPrice();
						$dealPrice = $price-$specialPrice;
						$price = $item->getProduct()->getFinalPrice() + $dealPrice;
						$item->setCustomPrice($price);
						$item->setOriginalCustomPrice($price);
						$item->getProduct()->setIsSuperMode(true);
					}
				}
			}
		}
	}
	
	public function updateCart(Varien_Event_Observer $observer){
		if(Mage::getStoreConfig('deals/general/enabled')){
			$quote = $observer->getCart()->getQuote();
			$items = $quote->getAllVisibleItems();
			foreach ($items as $item) {
				if ($item->getParentItem()) {
					$item = $item->getParentItem();
				}
				$productId = $item->getProductId();
				$qty = $item->getQty();
				
				$deal = Mage::getModel('deals/deals')
					->getCollection()
					->addFieldToFilter('product_id', $productId)
					->addFieldToFilter('status', 2)
					->getFirstItem();
					
				if($deal->getId()){
					$dealQty = $deal->getMaxDealQty();
					if($dealQty!=''){
						if($qty>$dealQty){
							$price = $deal->getPrice();
							$specialPrice =  $deal->getSpecialPrice();
							$dealPrice = $price-$specialPrice;
							$price = $item->getProduct()->getFinalPrice() + $dealPrice;
						}
						else{
							$price = $deal->getSpecialPrice();
						}
						
						$item->setCustomPrice($price);
						$item->setOriginalCustomPrice($price);
						$item->getProduct()->setIsSuperMode(true);
					}
				}
			}
		}
	}
}