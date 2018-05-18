<?php

class MGS_Social_Model_Facebook_Userinfo
{
    protected $client = null;
    protected $userInfo = null;

    public function __construct() {
        if(!Mage::getSingleton('customer/session')->isLoggedIn())
            return;

        $this->client = Mage::getSingleton('social/facebook_client');
        if(!($this->client->isEnabled())) {
            return;
        }

        $customer = Mage::getSingleton('customer/session')->getCustomer();
        if(($socialFid = $customer->getMgsSocialFid()) &&
                ($socialFtoken = $customer->getMgsSocialFtoken())) {
            try{
                $this->client->setAccessToken($socialFtoken);
                $this->userInfo = $this->client->api(
                    '/me',
                    'GET',
                    array(
                        'fields' =>
                        'id,name,first_name,last_name,link,birthday,gender,email,picture.type(large)'
                    )
                );            
            } catch(Exception $e) {
                Mage::getSingleton('core/session')->addError($e->getMessage());
            }

        }
    }

    public function getUserInfo()
    {
        return $this->userInfo;
    }
}
