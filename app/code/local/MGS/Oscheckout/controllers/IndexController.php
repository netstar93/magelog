<?php
class MGS_Oscheckout_IndexController extends Mage_Core_Controller_Front_Action {
	
    public function indexAction() {
		//die('test');
		if(!Mage::getStoreConfig('oscheckout/general/enabled')){
			$this->_redirectUrl(Mage::getUrl('checkout/onepage/index'));
		}
        $quote = $this->getOnepage()->getQuote();
        if (!$quote->hasItems() || $quote->getHasError()) {
            $this->_redirect('checkout/cart');
            return;
        }
        if (!$quote->validateMinimumAmount()) {

            $error = Mage::getStoreConfig('sales/minimum_order/error_message');
            Mage::getSingleton('checkout/session')->addError($error);
            $this->_redirect('checkout/cart');
            return;
        }
        Mage::getSingleton('checkout/session')->setCartWasUpdated(false);
        Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::getUrl('*/*/*', array('_secure' => true)));
        $helper = Mage::helper('oscheckout/checkout');
    	$this->shippingreloadAction();
        $billing_data = $this->getRequest()->getPost('billing', array());       
	    $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);
        if($this->getOnepage()->getQuote()->isVirtual()) 
         {
	        $Billingdata = $helper->load_add_data($billing_data);
	        $billing_result = $this->getOnepage()->saveBilling($Billingdata, $customerAddressId);
         }
        else
        {
        	if(!empty($billing_data['use_for_shipping']))
        	{
	        	$Shippingdata = $helper->load_add_data($billing_data);	        	
				$shipping_result1 = $this->getOnepage()->saveBilling($Shippingdata, $customerAddressId);
		        $shipping_result = $this->getOnepage()->saveShipping($Shippingdata, $customerAddressId);
        	}
        	else
        	{
		        $shippingAddressId = $this->getRequest()->getPost('shipping_address_id', false);
		        $shipping_data = $this->getRequest()->getPost('shipping', array());
		     	$Shippingdata = $helper->load_add_data($shipping_data);		     	
				$shipping_result1 = $this->getOnepage()->saveBilling($Shippingdata, $customerAddressId);
		        $shipping_result = $this->getOnepage()->saveShipping($Shippingdata, $shippingAddressId);  
        	}
        }
		
        $this->_checkCountry();
        $this->getOnepage()->initCheckout();
        $this->loadLayout();
		$this->getLayout()->getBlock('head')->setTitle(Mage::getStoreConfig('oscheckout/general/checkout_title'));
        $this->_initLayoutMessages('customer/session');
		$this->getOnepage()->getQuote()->collectTotals()->save();
        $this->renderLayout();           
    }
    
	private function _checkCountry()
    {
    	
        $quote = $this->getOnepage()->getQuote();
        $shipping = $quote->getShippingAddress();
        $billing = $quote->getBillingAddress();
        $default_country = false;
        $country_id = $shipping->getCountryId();
        $helper = Mage::helper('oscheckout/checkout');
     

		$countryId = Mage::getStoreConfig('general/country/default');
		$shipping->setCountryId($countryId)->setCollectShippingRates(true)->save();
		$billing->setCountryId($countryId)->save();
		$shipping->setSameAsBilling(true)->save();
    }
    
	public function shippingreloadAction()
    {
        if ($this->_expireAjax())
        {
            return;
        }
        $shipping_method = $this->getRequest()->getPost('shipping_method');
        if(!$shipping_method)
        {
        	$shipping_method = Mage::getStoreConfig('oscheckout/general/default_shipping_method');
        }
        $save_shippingmethod = $this->getOnepage()->saveShippingMethod($shipping_method);          
	    if(!$save_shippingmethod)
	     {
	       $event =    Mage::dispatchEvent('checkout_controller_onepage_save_shipping_method',
	                        array('request'=>$this->getRequest(),
	                            'quote'=>$this->getOnepage()->getQuote()));
	       $this->getOnepage()->getQuote()->collectTotals();
	     }
  	    $this->getOnepage()->getQuote()->collectTotals()->save();
        $this->getOnepage()->getQuote()->getShippingAddress()->setShippingMethod($shipping_method);
    }

    protected function _expireAjax() {
        $activateInCart = Mage::getStoreConfig('oscheckout/general/Activate_apptha_oscheckout_cart');
     	if($activateInCart != 1):
    	if (!$this->getOnepage()->getQuote()->hasItems()
                || $this->getOnepage()->getQuote()->getHasError()
                || $this->getOnepage()->getQuote()->getIsMultiShipping()) {
            $this->_ajaxRedirectResponse();
            return true;
        }
        $action = $this->getRequest()->getActionName();
        if (Mage::getSingleton('checkout/session')->getCartWasUpdated(true)
                && !in_array($action, array('index', 'progress'))) {
            $this->_ajaxRedirectResponse();
            return true;
        }
        return false;
        endif;
    }

    public function _ajaxRedirectResponse() {
        $this->getResponse()
                ->setHeader('HTTP/1.1', '403 Session Expired')
                ->setHeader('Login-Required', 'true')
                ->sendResponse();
        return $this;
    }

    public function getOnepage() {
        return Mage::getSingleton('checkout/type_onepage');
    }

    public function loginAction() {
        $username = $this->getRequest()->getPost('oscheckout_username', false);
        $password = $this->getRequest()->getPost('oscheckout_password', false);
        $session = Mage::getSingleton('customer/session');

        $result = array(
            'success' => false
        );

        if ($username && $password) {
            try {
                $session->login($username, $password);
            } catch (Exception $e) {
                $result['error'] = $e->getMessage();
            }

            if (!isset($result['error'])) {
                $result['success'] = true;
            }
        } else {
            $result['error'] = $this->__('Invalid email or password.');
        }

        $this->getResponse()->setBody(Zend_Json::encode($result));
    }

    public function forgotPasswordAction()
    {
        $email = $this->getRequest()->getPost('email', false);

        if (!Zend_Validate::is($email, 'EmailAddress'))
        {
            $result = array('success'=>false);
        }
        else
        {

            $customer = Mage::getModel('customer/customer')
            ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
            ->loadByEmail($email);

            if ($customer->getId())
             {
                try
                {
                    $newPassword = $customer->generatePassword();
                    $customer->changePassword($newPassword, false);
                    $customer->sendPasswordReminderEmail();
                    $result = array('success'=>true);
                }
                catch (Exception $e)
                {
                    $result = array('success'=>false, 'error'=>$e->getMessage());
                }
            }
            else
            {
                $result = array('success'=>false, 'error'=>'notfound');
            }
        }

        $this->getResponse()->setBody(Zend_Json::encode($result));
    }

    public function loadAction()
     {
     	if ($this->_expireAjax())
        {
            return;
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    public function saveshippingmethodAction()
    {
        if ($this->_expireAjax())
        {
            return;
        }
        $shipping_method = $this->getRequest()->getPost('shipping_method');
        if(!$shipping_method)
        {
        	$shipping_method = Mage::getStoreConfig('oscheckout/general/default_shipping_method');
        }
        $save_shippingmethod = $this->getOnepage()->saveShippingMethod($shipping_method);          
	    if(!$save_shippingmethod)
	     {
	       $event =    Mage::dispatchEvent('checkout_controller_onepage_save_shipping_method',
	                        array('request'=>$this->getRequest(),
	                            'quote'=>$this->getOnepage()->getQuote()));
	       $this->getOnepage()->getQuote()->collectTotals();
	     }
  	    $this->getOnepage()->getQuote()->collectTotals()->save(); 
        $this->getOnepage()->getQuote()->getShippingAddress()->setShippingMethod($shipping_method);
        $this->loadLayout();
        $this->renderLayout();
    }
    
 	public function loadpaymentAction()
    {       
        $this->loadLayout(false);
        $this->renderLayout();
    }
    
    public function summaryAction()
    {
        if ($this->_expireAjax())
        {

            return;
        }

        $this->loadLayout();
        $this->renderLayout();
    }
    //ajax save billing function
    //save billing,shipping,payment information
     public function savebillingAction()
    {
        $billing_data = $this->getRequest()->getPost('billing', array());
        $shipping_data = $this->getRequest()->getPost('shipping', array());
       
        $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);
        $shippingAddressId = $this->getRequest()->getPost('shipping_address_id', false);
        $quote = $this->getOnepage()->getQuote();
        $shipping = $quote->getShippingAddress();
        $billing = $quote->getBillingAddress();
        $billingCountryId = $billing_data['country_id'];
        $this->getOnepage()->getQuote()->getShippingAddress()->setCountryId($billingCountryId)->setCollectShippingRates(true);
        $billing->setCountryId($billingCountryId)->save();
        $shipping->setSameAsBilling(true)->save();
		
		if (!empty($customerAddressId))
          {

                $shippingAddress = Mage::getModel('customer/address')->load($customerAddressId);
                if (is_object($shippingAddress) && $shippingAddress->getCustomerId() == Mage::helper('customer')->getCustomer()->getId()) {
                    $shipping_data = array_merge($shipping_data, $shippingAddress->getData());
                    
                }
           }
        
        if (!empty($shippingAddressId))
          {

                $shippingAddress = Mage::getModel('customer/address')->load($shippingAddressId);
                if (is_object($shippingAddress) && $shippingAddress->getCustomerId() == Mage::helper('customer')->getCustomer()->getId()) {
                    $shipping_data = array_merge($shipping_data, $shippingAddress->getData());
                    
                }
           }
           
        /* start of save billing and shipping information for tax calculation */
           
    	$config = Mage::getStoreConfig('tax/calculation/based_on');
        $helper = Mage::helper('oscheckout/checkout');
        if($config=="billing")
        {
              $billing_info = $helper->load_add_data($billing_data);
              $billing_result = $this->getOnepage()->saveBilling($billing_info, $customerAddressId);
        }
        else
        {
            if(!empty($billing_data['use_for_shipping']))
             {
               $Billingdata = $helper->load_add_data($billing_data);
               $shipping_result = $this->getOnepage()->saveShipping($Billingdata, $customerAddressId);
             }
        	else
             {
            	if($this->getOnepage()->getQuote()->isVirtual()) 
            	 {
            	 	$billing_info = $helper->load_add_data($billing_data);
              		$billing_result = $this->getOnepage()->saveBilling($billing_info, $customerAddressId);
            	 }
            	else
            	{
                	$shipping_info = $helper->load_add_data($shipping_data);
                	$shipping_result = $this->getOnepage()->saveShipping($shipping_info, $shippingAddressId);
            	}
             }
        }
       
           
           /* End  of save billing and shipping information for tax calculation */
         
           
           //if shipping same as billing
         // save billing country,region,city,postcode to shipping
        if(!empty($billing_data['use_for_shipping'])) 
        {
            if(!empty($billing_data['country_id'])){
                $this->getOnepage()->getQuote()->getShippingAddress()->setCountryId($billing_data['country_id'])->setCollectShippingRates(true);
            }
            if(!empty($billing_data['region'])){
                $this->getOnepage()->getQuote()->getShippingAddress()->setRegion($billing_data['region'])->setCollectShippingRates(true);
            }
			if(!empty($billing_data['region_id'])){
                $this->getOnepage()->getQuote()->getShippingAddress()->setRegionId($billing_data['region_id'])->setCollectShippingRates(true);
            }
            if(!empty($billing_data['city'])){
                $this->getOnepage()->getQuote()->getShippingAddress()->setCity($billing_data['city'])->setCollectShippingRates(true);
            }
            if(!empty($billing_data['postcode'])){
                $this->getOnepage()->getQuote()->getShippingAddress()->setPostcode($billing_data['postcode'])->setCollectShippingRates(true);
            }
        }
        else
        {
            if(!empty($shipping_data['country_id'])){
                $this->getOnepage()->getQuote()->getShippingAddress()->setCountryId($shipping_data['country_id'])->setCollectShippingRates(true);
            }
            else {$this->getOnepage()->getQuote()->getBillingAddress()->setCountryId($shipping_data['country_id'])->setCollectShippingRates(true);}
            if(!empty($shipping_data['region'])){
                $this->getOnepage()->getQuote()->getShippingAddress()->setRegion($shipping_data['region'])->setCollectShippingRates(true);
            }
			if(!empty($shipping_data['region_id'])){
                $this->getOnepage()->getQuote()->getShippingAddress()->setRegionId($shipping_data['region_id'])->setCollectShippingRates(true);
            }
            if(!empty($shipping_data['city'])){
                $this->getOnepage()->getQuote()->getShippingAddress()->setCity($shipping_data['city'])->setCollectShippingRates(true);
            }
            if(!empty($shipping_data['postcode'])){
                $this->getOnepage()->getQuote()->getShippingAddress()->setPostcode($shipping_data['postcode'])->setCollectShippingRates(true);
            }
        }
		
		if($customerAddressId){
			$address = Mage::getModel('customer/address')->load($customerAddressId);
			$this->getOnepage()->getQuote()->getShippingAddress()->setCountryId($address->getCountryId())->setCollectShippingRates(true);
			$this->getOnepage()->getQuote()->getShippingAddress()->setRegionId($address->getRegionId())->setCollectShippingRates(true);
			$this->getOnepage()->getQuote()->getShippingAddress()->setCity($address->getCity())->setCollectShippingRates(true);
		}
		
    $paymentMethod = $this->getRequest()->getPost('payment_method', false);
    if($this->getOnepage()->getQuote()->isVirtual()) 
      {
          $this->getOnepage()->getQuote()->getBillingAddress()->setPaymentMethod(!empty($paymentMethod) ? $paymentMethod : null);
      }
     else
      {
            $this->getOnepage()->getQuote()->getShippingAddress()->setPaymentMethod(!empty($paymentMethod) ? $paymentMethod : null);
      }
		$this->getOnepage()->getQuote()->collectTotals()->save();
        $this->loadLayout(false);
        $this->renderLayout();

    }


    /* function:get all the information from onepage form and save the order using ajax */

    public function saveOrderAction() {
        if ($this->_expireAjax()) {
        	
            return;
        }
        $helper = Mage::helper('oscheckout/checkout');
        if ($this->getRequest()->isPost()) {
            $Method = $this->getRequest()->getPost('checkout_method', false);
            $Billingdata = $this->getRequest()->getPost('billing', array());
            $Billingdata = $helper->load_exclude_data($Billingdata);
			try{
				$Paymentdata = $this->getRequest()->getPost('payment', array());
			} catch (Mage_Payment_Model_Info_Exception $e) {
				$message = $e->getMessage();
				if (!empty($message)) {
					$result['success'] = false;
					$result['error'] = true;
					$result['error_messages'] = $message;
					$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
					return;
				}
			}

            $result = $this->getOnepage()->saveCheckoutMethod($Method);
            if(isset($Billingdata['is_subscribed']) && !empty($Billingdata['is_subscribed']))
            {
            $customer = $this->getOnepage()->getCheckout()->setCustomerIsSubscribed(1);
            }
            $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);
            if (isset($Billingdata['email'])) {
                $Billingdata['email'] = trim($Billingdata['email']);
            }
            $Billingresult = $this->getOnepage()->saveBilling($Billingdata, $customerAddressId);
            $Paymentresult = $this->getOnepage()->savePayment($Paymentdata);
			
            $Shippingdata = $this->getRequest()->getPost('shipping', array());
            $ShippingAddressId = $this->getRequest()->getPost('shipping_address_id', false);
           if (!empty($Billingdata['use_for_shipping']))
            {
                $shipping_result = $this->getOnepage()->saveShipping($Billingdata, $customerAddressId);
            }
            else if (!empty($ShippingAddressId)) {

                $shippingAddress = Mage::getModel('customer/address')->load($ShippingAddressId);
                if (is_object($shippingAddress) && $shippingAddress->getCustomerId() == Mage::helper('customer')->getCustomer()->getId())
                {
                    $Shippingdata = array_merge($Shippingdata, $shippingAddress->getData());
                    $shipping_result = $this->getOnepage()->saveShipping($Shippingdata, $ShippingAddressId);
                }
            } else if (empty($Billingdata['use_for_shipping']) && !$ShippingAddressId)
                {
                $shipping_result = $this->getOnepage()->saveShipping($Shippingdata, $ShippingAddressId);
            } else {
                $shipping_result = $this->getOnepage()->saveShipping($Billingdata, $customerAddressId);
            }
            $ShippingMethoddata = $this->getRequest()->getPost('shipping_method', '');
            $ShippingMethodresult = $this->getOnepage()->saveShippingMethod($ShippingMethoddata);
            // }
        }
        Mage::dispatchEvent('checkout_controller_onepage_save_shipping_method', array('request' => $this->getRequest(), 'quote' => $this->getOnepage()->getQuote()));
        $data = $this->getRequest()->getPost('payment', array());
		$result = $this->getOnepage()->savePayment($data);
		
		/* if(($data['method']=='authorizenet_directpost') && $data['cc_number']==''){
			$result['success'] = false;
			$result['error'] = true;
			$result['error_messages'] = 'authorize';
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
			return;
		} */

            // get section and redirect data
            $redirectUrl = $this->getOnepage()->getQuote()->getPayment()->getCheckoutRedirectUrl();
             if ($redirectUrl) {
               if ($requiredAgreements = Mage::helper('checkout')->getRequiredAgreementIds()) {
                $postedAgreements = array_keys($this->getRequest()->getPost('agreement', array()));
                if ($diff = array_diff($requiredAgreements, $postedAgreements)) {
                    $result['success'] = false;
                    $result['error'] = true;
                    $result['error_messages'] = $this->__('Please agree to all the terms and conditions.');
                    $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                    return;
                }
            }
                 $result['success'] = true;
                 $result['error'] = false;
                 $result['redirect'] = $redirectUrl;
              $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
              return;
            }
           // $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        $result = array();
        try {
            if ($requiredAgreements = Mage::helper('checkout')->getRequiredAgreementIds()) {
                $postedAgreements = array_keys($this->getRequest()->getPost('agreement', array()));
                if ($diff = array_diff($requiredAgreements, $postedAgreements)) {
                    $result['success'] = false;
                    $result['error'] = true;
                    $result['error_messages'] = $this->__('Please agree to all the terms and conditions.');
                    $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                    return;
                }
            }
            if ($data = $this->getRequest()->getPost('payment', false)) {
                $this->getOnepage()->getQuote()->getPayment()->importData($data);
            }
            $this->getOnepage()->saveOrder();
            $redirectUrl = $this->getOnepage()->getCheckout()->getRedirectUrl();
            $result['success'] = true;
            $result['error'] = false;
        } catch (Mage_Payment_Model_Info_Exception $e) {
            $message = $e->getMessage();
            if (!empty($message)) {
                $result['error_messages'] = $message;
            }
            
        } catch (Mage_Core_Exception $e) {
           // Mage::logException($e);
            $result['success'] = false;
            $result['error'] = true;
            $result['error_messages'] = $e->getMessage();
            Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
            $result['success'] = false;
            $result['error'] = true;
            $result['error_messages'] = $e->getMessage();

           
        } catch (Exception $e) {
           // Mage::logException($e);
            $result['success'] = false;
            $result['error'] = true;
            $result['error_messages'] = $e->getMessage();
            Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
            $result['success'] = false;
            $result['error'] = true;
            $result['error_messages'] = $this->__('There was an error processing your order. Please contact us or try again later.');
        }
        $this->getOnepage()->getQuote()->save();
        /**
         * when there is redirect to third party, we don't want to save order yet.
         * we will save the order in return action.
         */
		if($this->getOnepage()->getCheckout()->getRedirectUrl()!=''){
			$result['redirect'] = $redirectUrl;
		}
		else{
			if (isset($redirectUrl)) {
				$result['redirect'] = $redirectUrl;
			}
			if ($result['success']) {
				$result['success'] = Mage::getBaseUrl() . 'checkout/onepage/success/';
			}
		}
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }


    function updatecouponAction()
    {

        $quote = $this->getOnepage()->getQuote();
        $couponCode = (string)$this->getRequest()->getParam('code');

        if ($this->getRequest()->getParam('remove') == 1) {
            $couponCode = '';
        }

        $response = array(
            'success' => false,
            'error'=> false,
            'message' => false,
        );
        try {

            $quote->getShippingAddress()->setCollectShippingRates(true);
            $quote->setCouponCode(strlen($couponCode) ? $couponCode : '')
            ->collectTotals()
            ->save();

            if ($couponCode) {
                if ($couponCode == $quote->getCouponCode()) {
                    $response['success'] = true;
                    $response['message'] = $this->__('Coupon code "%s" was applied successfully.',
                    Mage::helper('core')->htmlEscape($couponCode));
                }
                else {
                    $response['success'] = false;
                    $response['error'] = true;
                    $response['message'] = $this->__('Coupon code "%s" is not valid.',
                    Mage::helper('core')->htmlEscape($couponCode));
                }
            } else {
                $response['success'] = true;
                $response['message'] = $this->__('Coupon code was canceled successfully.');
            }


        }
        catch (Mage_Core_Exception $e) {
            $response['success'] = false;
            $response['error'] = true;
            $response['message'] = $e->getMessage();
        }
        catch (Exception $e) {
            $response['success'] = false;
            $response['error'] = true;
            $response['message'] = $this->__('Can not apply coupon code.');
        }

       
        $html = $this->getLayout()
        ->createBlock('oscheckout/step_review_info')
        ->setTemplate('oscheckout/step/review/info.phtml')
        ->toHtml();

        $response['summary'] = $html;


        $this->getResponse()->setBody(Zend_Json::encode($response));
    }
     /* End of couponcode Action */
    
    public function loadreviewAction()
    {
        if ($this->_expireAjax())
        {

            return;
        }
        $Paymentdata = $this->getRequest()->getPost('payment');
		$Paymentresult = $this->getOnepage()->savePayment($Paymentdata);
		$available = new Mage_Checkout_Block_Onepage_Shipping_Method_Available;
		$_shippingRateGroups = $available->getShippingRates();
		foreach ($_shippingRateGroups as $code => $_rates){
			if(count($_rates)==1){
				foreach ($_rates as $_rate){
					$shipping_method = $_rate->getCode();
					
					$save_shippingmethod = $this->getOnepage()->saveShippingMethod($shipping_method);

					$this->getOnepage()->getQuote()->collectTotals()->save(); 

					$this->getOnepage()->getQuote()->getShippingAddress()->setShippingMethod($shipping_method);
				}
			}
		}
		$this->getOnepage()->getQuote()->collectTotals()->save();
        $this->loadLayout();
        $this->renderLayout();
    }
	
	public function savepayAction(){
		if ($this->_expireAjax())
        {
            return;
        }
		$method = $this->getRequest()->getPost('code');
		$data['method'] = $method;
		$this->getOnepage()->savePayment($data);
		$this->getOnepage()->getQuote()->collectTotals()->save();
	}
    
}