<?php

/* * ****************************************************
 * Package   : Social
 * Author    : HIEPNH
 * Copyright : (c) 2014
 * ***************************************************** */
?>
<?php

require_once(Mage::getBaseDir('lib') . '/mgs/social/google/Google_Client.php');
require_once(Mage::getBaseDir('lib') . '/mgs/social/google/Google_Oauth2Service.php');

class MGS_Social_GoogleController extends Mage_Core_Controller_Front_Action {

    public function connectAction() {
        try {
            $this->_connectCallback();
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }
        $this->getResponse()->setBody($this->getLayout()->createBlock('social/google_close')->setRedirectUrl(Mage::helper('customer')->getDashboardUrl())->toHtml());
    }

    protected function _connectCallback() {
        $params = $this->getRequest()->getParams();
        $helper = Mage::helper('social/google');
        $storeId = Mage::app()->getStore()->getStoreId();
        $websiteId = Mage::app()->getStore()->getWebsiteId();
        $clientId = $helper->getGoogleConfig('client_id', $storeId);
        $clientSecret = $helper->getGoogleConfig('client_secret', $storeId);
        $redirectUri = Mage::getUrl('social/google/connect', array('_secure' => true));
        $client = new Google_Client();
        $client->setApprovalPrompt('auto');
        $client->setAccessType('offline');
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUri);
        $client->setScopes(array(
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile'
        ));
        $oauth2 = new Google_Oauth2Service($client);
        if (isset($params['code'])) {
            $client->authenticate($params['code']);
            $gtoken = $client->getAccessToken();
            $client->setAccessToken($gtoken);
        }
        if (isset($params['error'])) {
            echo '<script type="text/javascript">window.close();</script>';
            exit;
        }
        if ($client->getAccessToken()) {
            $data = $oauth2->userinfo->get();
            $customersByGoogleId = Mage::helper('social/google')->getCustomersByGoogleId($data['id'], $websiteId);
            if (Mage::getSingleton('customer/session')->isLoggedIn()) {
                if ($customersByGoogleId->getSize()) {
                    Mage::getSingleton('core/session')->addNotice($this->__('Your google account is already connected to one of our store accounts.'));
                    return $this;
                }
                $customer = Mage::getSingleton('customer/session')->getCustomer();
                Mage::helper('social/google')->connectByGoogleId($customer, $data['id'], $client->getAccessToken());
                Mage::getSingleton('core/session')->addSuccess($this->__('Your google account is now connected to your store account. You can now login using our google login button or using store account credentials you will receive to your email address.'));
                return $this;
            }
            if ($customersByGoogleId->getSize()) {
                $customer = $customersByGoogleId->getFirstItem();
                Mage::helper('social/google')->loginByCustomer($customer);
                Mage::getSingleton('core/session')->addSuccess($this->__('You have successfully logged in using your google account.'));
                return $this;
            }
            $customersByEmail = Mage::helper('social/google')->getCustomersByEmail($data['email'], $websiteId);
            if ($customersByEmail->getSize()) {
                $customer = $customersByEmail->getFirstItem();
                Mage::helper('social/google')->connectByGoogleId($customer, $data['id'], $client->getAccessToken());
                Mage::getSingleton('core/session')->addSuccess($this->__('We have discovered you already have an account at our store. Your google account is now connected to your store account.'));
                return $this;
            }
            $firstName = $data['given_name'];
            if (empty($firstName)) {
                throw new Exception($this->__('Sorry, could not retrieve your google first name. Please try again.'));
            }
            $lastName = $data['family_name'];
            if (empty($lastName)) {
                throw new Exception($this->__('Sorry, could not retrieve your google last name. Please try again.'));
            }
            Mage::helper('social/google')->connectByCreatingAccount($data['email'], $data['given_name'], $data['family_name'], $data['id'], $client->getAccessToken());
            Mage::getSingleton('core/session')->addSuccess($this->__('Your google account is now connected to your new user account at our store. Now you can login using our google login button or using store account credentials you will receive to your email address.'));
            return $this;
        } else {
            $authUrl = $client->createAuthUrl();
            Mage::app()->getResponse()->setRedirect($authUrl);
        }
    }

    protected function _loginPostRedirect() {
        $session = Mage::getSingleton('customer/session');
        $session->setBeforeAuthUrl(Mage::helper('customer')->getDashboardUrl());
        $this->_redirectUrl($session->getBeforeAuthUrl(true));
    }

}
