<?php
/**
 * @name         :  MGS One Step Checkout
 * @version      :  1.4
 * @since        :  Magento ver 1.4, 1.5, 1.6, 1.7
 * @author       :  MGS - http://www.mage-shop.com
 * @copyright    :  Copyright (C) 2011 Powered by MGS
 * @license      :  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Creation Date:  Sept 06 2012
 * 
 * */
class MGS_Oscheckout_Model_Observer extends Varien_Object
{
	public function saveOrderComment($observer){
		if(Mage::getStoreConfig('oscheckout/comment/enabled'))
		{
			$orderComment = Mage::app()->getRequest()->getParam('oscheckout_comments');
			$orderComment = trim($orderComment);
			if ($orderComment != "")
			{
				$order = $observer->getOrder();
				$_order = Mage::getModel('sales/order')->load($order->getId());
				$orderId = $_order->getIncrementId();
				
				$resource = Mage::getSingleton('core/resource');
				$read = $resource->getConnection('catalog_read');
				$gridTable = $resource->getTableName('sales_flat_order_grid');

				try{
					$rs = $read->query('update '.$gridTable.' set mgs_order_comment = "'.$orderComment.'" where increment_id = "'.$orderId.'"');
				}
				catch(Exception $e){
					
				}
			}
		}
	}
	
	public function saveAdditionalInfo($observer){
		if ((bool) Mage::getSingleton('checkout/session')->getCustomerIsSubscribed()){
            $quote = $observer->getEvent()->getQuote();
            $customer = $quote->getCustomer();
            switch ($quote->getCheckoutMethod()){
                case Mage_Sales_Model_Quote::CHECKOUT_METHOD_REGISTER:
                    $customer->setIsSubscribed(1);
                    break;
				case Mage_Sales_Model_Quote::CHECKOUT_METHOD_LOGIN_IN:
					$customer->setIsSubscribed(1);
					break;
                case Mage_Sales_Model_Quote::CHECKOUT_METHOD_GUEST:
                    $session = Mage::getSingleton('core/session');
                    try {
                        $status = Mage::getModel('newsletter/subscriber')->subscribe($quote->getBillingAddress()->getEmail());
                        if ($status == Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE){
                            $session->addSuccess(Mage::helper('oscheckout')->__('Confirmation request has been sent regarding your newsletter subscription'));
                        }
                    }
                    catch (Mage_Core_Exception $e) {
                        $session->addException($e, Mage::helper('oscheckout')->__('There was a problem with the newsletter subscription: %s', $e->getMessage()));
                    }
                    catch (Exception $e) {
                        $session->addException($e, Mage::helper('oscheckout')->__('There was a problem with the newsletter subscription'));
                    }
                    break;
            }
            Mage::getSingleton('checkout/session')->setCustomerIsSubscribed(0);
		}
	}
}