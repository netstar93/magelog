<?php

/* * ****************************************************
 * Package   : Social
 * Author    : HIEPNH
 * Copyright : (c) 2014
 * ***************************************************** */
?>
<?php

class MGS_Social_FacebookController extends Mage_Core_Controller_Front_Action
{
    protected $referer = null;

    public function connectAction()
    {
        try {
            $this->_connectCallback();
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }
        $this->_loginPostRedirect();
    }

    protected function _connectCallback()
    {
        $errorCode = $this->getRequest()->getParam('error');
        $code = $this->getRequest()->getParam('code');
        $state = $this->getRequest()->getParam('state');
        if(!($errorCode || $code) && !$state) {
            return;
        }
        $this->referer = Mage::getSingleton('core/session')
            ->getFacebookRedirect();
        if(!$state || $state != Mage::getSingleton('core/session')->getFacebookCsrf()) {
            return;
        }
        if($errorCode) {
            if($errorCode === 'access_denied') {
                Mage::getSingleton('core/session')
                    ->addNotice(
                        $this->__('Facebook Connect process aborted.')
                    );
                return;
            }
            throw new Exception(
                sprintf(
                    $this->__('Sorry, "%s" error occured. Please try again.'),
                    $errorCode
                )
            );
            return;
        }
        if ($code) {
            $client = Mage::getSingleton('social/facebook_client');
            $data = $client->api('/me?fields=id,first_name,last_name,email,gender,locale,picture');
            $fid = $data->id;
            $accessTokenData = json_decode($client->getAccessToken());
            $ftoken = $accessTokenData->access_token;
            $customersByFacebookId = Mage::helper('social/facebook')->getCustomersByFacebookId($fid, $websiteId);
            if (Mage::getSingleton('customer/session')->isLoggedIn()) {
                if ($customersByFacebookId->getSize()) {
                    Mage::getSingleton('core/session')->addNotice($this->__('Your facebook account is already connected to one of our store accounts.'));
                    return $this;
                }
                $customer = Mage::getSingleton('customer/session')->getCustomer();
                Mage::helper('social/facebook')->connectByFacebookId($customer, $fid, $ftoken);
                Mage::getSingleton('core/session')->addSuccess($this->__('Your facebook account is now connected to your store account. You can now login using our facebook login button or using store account credentials you will receive to your email address.'));
                return $this;
            }
            if ($customersByFacebookId->getSize()) {
                $customer = $customersByFacebookId->getFirstItem();
                Mage::helper('social/facebook')->loginByCustomer($customer);
                Mage::getSingleton('core/session')->addSuccess($this->__('You have successfully logged in using your facebook account.'));
                return $this;
            }
            $customersByEmail = Mage::helper('social/facebook')->getCustomersByEmail($data->email, $websiteId);
            if ($customersByEmail->getSize()) {
                $customer = $customersByEmail->getFirstItem();
                Mage::helper('social/facebook')->connectByFacebookId($customer, $fid, $ftoken);
                Mage::getSingleton('core/session')->addSuccess($this->__('We have discovered you already have an account at our store. Your facebook account is now connected to your store account.'));
                return $this;
            }
            $firstName = $data->first_name;
            if (empty($firstName)) {
                throw new Exception($this->__('Sorry, could not retrieve your facebook first name. Please try again.'));
            }
            $lastName = $data->last_name;
            if (empty($lastName)) {
                throw new Exception($this->__('Sorry, could not retrieve your facebook last name. Please try again.'));
            }
            Mage::helper('social/facebook')->connectByCreatingAccount($data->email, $data->first_name, $data->last_name, $fid, $ftoken);
            Mage::getSingleton('core/session')->addSuccess($this->__('Your facebook account is now connected to your new user account at our store. Now you can login using our facebook login button or using store account credentials you will receive to your email address.'));
            return $this;
        }
    }

    protected function _loginPostRedirect()
    {
        $session = Mage::getSingleton('customer/session');
        $session->setBeforeAuthUrl(Mage::helper('customer')->getDashboardUrl());
        $this->_redirectUrl($session->getBeforeAuthUrl(true));
    }

}
