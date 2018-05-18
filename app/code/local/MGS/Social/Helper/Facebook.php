<?php

/* * ****************************************************
 * Package   : Social
 * Author    : HIEPNH
 * Copyright : (c) 2014
 * ***************************************************** */
?>
<?php

class MGS_Social_Helper_Facebook extends Mage_Core_Helper_Abstract {

    const XML_PATH_FACEBOOK = 'social/facebook/';
    const XML_PATH_FACEBOOK_ACTIVE = 'social/facebook/active';

    public function getFacebookConfig($code, $store = null) {
        if (!$store) {
            $store = Mage::app()->getStore()->getId();
        }
        return Mage::getStoreConfig(self::XML_PATH_FACEBOOK . $code, $store);
    }

    public function isActived($store = null) {
        return Mage::getStoreConfigFlag(self::XML_PATH_FACEBOOK_ACTIVE, $store);
    }

    public function getCustomersByEmail($email, $websiteId) {
        $collection = Mage::getModel('customer/customer')->getCollection()
                ->addFieldToFilter('email', $email)
                ->setPageSize(1);
        //if (Mage::getStoreConfig('customer/account_share/scope')) {
        //    $collection->addFieldToFilter('website_id', $websiteId);
        //}
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $collection->addFieldToFilter(
                    'entity_id', array('neq' => Mage::getSingleton('customer/session')->getCustomerId())
            );
        }
        return $collection;
    }

    public function getCustomersByFacebookId($fid, $websiteId) {
        $collection = Mage::getModel('customer/customer')->getCollection()
                ->addAttributeToFilter('mgs_social_fid', $fid)
                ->setPageSize(1);
        //if (Mage::getStoreConfig('customer/account_share/scope')) {
        //    $collection->addFieldToFilter('website_id', $websiteId);
        //}
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $collection->addFieldToFilter(
                    'entity_id', array('neq' => Mage::getSingleton('customer/session')->getCustomerId())
            );
        }
        return $collection;
    }

    public function loginByCustomer(Mage_Customer_Model_Customer $customer) {
        if ($customer->getConfirmation()) {
            $customer->setConfirmation(null);
            $customer->save();
        }
        Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);
    }

    public function connectByFacebookId(Mage_Customer_Model_Customer $customer, $fid, $ftoken) {
        $customer->setMgsSocialFid($fid)
                ->setMgsSocialFtoken($ftoken)
                ->save();
        Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);
    }

    public function connectByCreatingAccount($email, $firstName, $lastName, $fid, $ftoken) {
        $customer = Mage::getModel('customer/customer');
        $customer->setWebsiteId(Mage::app()->getWebsite()->getId())
                ->setEmail($email)
                ->setFirstname($firstName)
                ->setLastname($lastName)
                ->setMgsSocialFid($fid)
                ->setMgsSocialFtoken($ftoken)
                ->setPassword($customer->generatePassword(10))
                ->save();
        $customer->setConfirmation(null);
        $customer->save();
        $customer->sendNewAccountEmail('confirmed', '', Mage::app()->getStore()->getId());
        Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);
    }

}
