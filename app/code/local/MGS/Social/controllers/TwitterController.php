<?php

/* * ****************************************************
 * Package   : Social
 * Author    : HIEPNH
 * Copyright : (c) 2014
 * ***************************************************** */
?>
<?php

require_once(Mage::getBaseDir('lib') . '/mgs/social/twitter/twitteroauth.php');

class MGS_Social_TwitterController extends Mage_Core_Controller_Front_Action {

    public function connectAction() {
        try {
            $this->_connectCallback();
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }
        $this->getResponse()->setBody($this->getLayout()->createBlock('social/twitter_close')->setRedirectUrl(Mage::helper('customer')->getDashboardUrl())->toHtml());
    }

    protected function _connectCallback() {
        $params = $this->getRequest()->getParams();
        $helper = Mage::helper('social/twitter');
        $storeId = Mage::app()->getStore()->getStoreId();
        $websiteId = Mage::app()->getStore()->getWebsiteId();
        $clientId = $helper->getTwitterConfig('client_id', $storeId);
        $clientSecret = $helper->getTwitterConfig('client_secret', $storeId);
        $redirectUri = Mage::getUrl('social/twitter/connect', array('_secure' => true));
        if (isset($params['denied'])) {
            echo '<script type="text/javascript">window.close();</script>';
            exit;
        }
        if (!isset($params['oauth_token'])) {
            $connection = new TwitterOAuth($clientId, $clientSecret);
            $requestToken = $connection->getRequestToken($redirectUri);
            Mage::getSingleton('core/session')->unsOauthToken();
            Mage::getSingleton('core/session')->setOauthToken($requestToken['oauth_token']);
            $token = $requestToken['oauth_token'];
            Mage::getSingleton('core/session')->unsOauthTokenSecret();
            Mage::getSingleton('core/session')->setOauthTokenSecret($requestToken['oauth_token_secret']);
            switch ($connection->http_code) {
                case 200:
                    $url = $connection->getAuthorizeURL($token);
                    Mage::app()->getResponse()->setRedirect($url);
                    break;
                default:
                    Mage::getSingleton('core/session')->addError($this->__('Could not connect to twitter. Refresh the page or try again later.'));
            }
        } else {
            $connection = new TwitterOAuth($clientId, $clientSecret, Mage::getSingleton('core/session')->getOauthToken(), Mage::getSingleton('core/session')->getOauthTokenSecret());
            $accessToken = $connection->getAccessToken($this->getRequest()->getParam('oauth_verifier'));
            Mage::getSingleton('core/session')->unsAccessToken();
            Mage::getSingleton('core/session')->setAccessToken($accessToken);
            $content = $connection->get('account/verify_credentials');
            $data = array();
            if (!empty($content->id)) {
                $data['id'] = $content->id;
                $data['name'] = $content->name;
                $data['screen_name'] = $content->screen_name;
                $data['email'] = $content->screen_name . '@twitter.com';
                $customersByTwitterId = Mage::helper('social/twitter')->getCustomersByTwitterId($data['id'], $websiteId);
                if (Mage::getSingleton('customer/session')->isLoggedIn()) {
                    if ($customersByTwitterId->getSize()) {
                        Mage::getSingleton('core/session')->addNotice($this->__('Your twitter account is already connected to one of our store accounts.'));
                        return $this;
                    }
                    $customer = Mage::getSingleton('customer/session')->getCustomer();
                    Mage::helper('social/twitter')->connectByTwitterId($customer, $data['id'], $accessToken);
                    Mage::getSingleton('core/session')->addSuccess($this->__('Your twitter account is now connected to your store account. You can now login using our twitter login button or using store account credentials you will receive to your email address.'));
                    return $this;
                }
                if ($customersByTwitterId->getSize()) {
                    $customer = $customersByTwitterId->getFirstItem();
                    Mage::helper('social/twitter')->loginByCustomer($customer);
                    Mage::getSingleton('core/session')->addSuccess($this->__('You have successfully logged in using your twitter account.'));
                    return $this;
                }
                $customersByEmail = Mage::helper('social/twitter')->getCustomersByEmail($data['email'], $websiteId);
                if ($customersByEmail->getSize()) {
                    $customer = $customersByEmail->getFirstItem();
                    Mage::helper('social/twitter')->connectByTwitterId($customer, $data['id'], $accessToken);
                    Mage::getSingleton('core/session')->addSuccess($this->__('We have discovered you already have an account at our store. Your twitter account is now connected to your store account.'));
                    return $this;
                }
                $name = $data['name'];
                if (empty($name)) {
                    throw new Exception($this->__('Sorry, could not retrieve your twitter name. Please try again.'));
                }
                $screenName = $data['screen_name'];
                if (empty($screenName)) {
                    throw new Exception($this->__('Sorry, could not retrieve your twitter screen name. Please try again.'));
                }
                Mage::helper('social/twitter')->connectByCreatingAccount($data['email'], $data['screen_name'], $data['screen_name'], $data['id'], $accessToken);
                Mage::getSingleton('core/session')->addSuccess($this->__('Your twitter account is now connected to your new user account at our store. Now you can login using our twitter login button or using store account credentials you will receive to your email address.'));
                return $this;
            } else {
                Mage::getSingleton('core/session')->addError($this->__('Sorry, could not login. Please try again.'));
                return $this;
            }
        }
    }

    protected function _loginPostRedirect() {
        $session = Mage::getSingleton('customer/session');
        $session->setBeforeAuthUrl(Mage::helper('customer')->getDashboardUrl());
        $this->_redirectUrl($session->getBeforeAuthUrl(true));
    }

}
