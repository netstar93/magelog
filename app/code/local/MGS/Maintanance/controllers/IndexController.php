<?php

class MGS_Maintanance_IndexController extends Mage_Core_Controller_Front_Action {

    public function checkTimerAction() {
        $storeId = Mage::app()->getStore()->getStoreId();
        $isEnabled = Mage::getStoreConfig('maintanance/general/enabled', $storeId);
        $timerEnabled = Mage::getStoreConfig('maintanance/timer/timer_enabled', $storeId);
        //$makesiteEnabled = Mage::getStoreConfig('maintanance/timer/site_enabled', $storeId);
        //if ($isEnabled == 1 && $timerEnabled == 1 && $makesiteEnabled == 1) {
        if ($isEnabled == 1 && $timerEnabled == 1) {
            $timerConfig = new Mage_Core_Model_Config();
            $timerConfig->saveConfig('maintanance/general/enabled', '0');
            $timerConfig->saveConfig('maintanance/timer/timer_enabled', '0');
            Mage::app()->getCacheInstance()->flush();
            echo true;
        }
    }

}
