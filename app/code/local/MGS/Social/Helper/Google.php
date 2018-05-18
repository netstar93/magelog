<?php

/* * ****************************************************
 * Package   : Social
 * Author    : HIEPNH
 * Copyright : (c) 2014
 * ***************************************************** */
?>
<?php

class MGS_Social_Helper_Google extends Mage_Core_Helper_Abstract {

    const XML_PATH_GOOGLE = 'social/google/';
    const XML_PATH_GOOGLE_ACTIVE = 'social/google/active';

    public function getGoogleConfig($code, $store = null) {
        if (!$store) {
            $store = Mage::app()->getStore()->getId();
        }
        return Mage::getStoreConfig(self::XML_PATH_GOOGLE . $code, $store);
    }

    public function isActived($store = null) {
        return Mage::getStoreConfigFlag(self::XML_PATH_GOOGLE_ACTIVE, $store);
    }

    public function getCustomersByEmail($email, $websiteId) {
        $collection = Mage::getModel('customer/customer')->getCollection()
                ->addFieldToFilter('email', $email)
                ->setPageSize(1);
        if (Mage::getStoreConfig('customer/account_share/scope')) {
            $collection->addFieldToFilter('website_id', $websiteId);
        }
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $collection->addFieldToFilter(
                    'entity_id', array('neq' => Mage::getSingleton('customer/session')->getCustomerId())
            );
        }
        return $collection;
    }

    public function getCustomersByGoogleId($gid, $websiteId) {
        $collection = Mage::getModel('customer/customer')->getCollection()
                ->addAttributeToFilter('mgs_social_gid', $gid)
                ->setPageSize(1);
        if (Mage::getStoreConfig('customer/account_share/scope')) {
            $collection->addFieldToFilter('website_id', $websiteId);
        }
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

    public function connectByGoogleId(Mage_Customer_Model_Customer $customer, $gid, $gtoken) {
        $customer->setMgsSocialGid($gid)
                ->setMgsSocialGtoken($gtoken)
                ->save();
        Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);
    }

    public function connectByCreatingAccount($email, $firstName, $lastName, $gid, $gtoken) {
        $customer = Mage::getModel('customer/customer');
        $customer->setWebsiteId(Mage::app()->getWebsite()->getId())
                ->setEmail($email)
                ->setFirstname($firstName)
                ->setLastname($lastName)
                ->setMgsSocialGid($gid)
                ->setMgsSocialGtoken($gtoken)
                ->setPassword($customer->generatePassword(10))
                ->save();
        $customer->setConfirmation(null);
        $customer->save();
        $customer->sendNewAccountEmail('confirmed', '', Mage::app()->getStore()->getId());
        Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);
    }

}
