<?php

/* * ****************************************************
 * Package   : Event
 * Author    : HIEPNH
 * Copyright : (c) 2014
 * ***************************************************** */
?>
<?php

class MGS_Event_Model_Observer {

    public function configChangedSectionEvent($observer) {
        try {
            $urlRewriteCollection = Mage::getModel('core/url_rewrite')
                    ->getCollection()
                    ->addFieldToFilter('id_path', 'event/index/');
            foreach ($urlRewriteCollection as $url) {
                $url->delete();
            }
            $urlRewrite = Mage::getModel('core/url_rewrite')->loadByIdPath('event/index')
                    ->setIdPath('event/index');
            if (Mage::helper('event')->getGeneralConfig('router', Mage::app()->getStore()->getStoreId()) != '') {
                $urlRewrite->setRequestPath(Mage::helper('event')->getGeneralConfig('router', Mage::app()->getStore()->getStoreId()));
            } else {
                $urlRewrite->setRequestPath('event');
            }
            $urlRewrite->setTargetPath('event/index/index');
            $urlRewrite->setIsSystem(1);
            $urlRewrite->save();
            $collection = Mage::getModel('event/event')->getCollection();
            foreach ($collection as $event) {
                $urlRewriteCollection = Mage::getModel('core/url_rewrite')
                        ->getCollection()
                        ->addFieldToFilter('id_path', 'event/view/' . $event->getId());
                foreach ($urlRewriteCollection as $url) {
                    $url->delete();
                }
                $urlRewrite = Mage::getModel('core/url_rewrite')->loadByIdPath('event/view/' . $event->getId())
                        ->setIdPath('event/view/' . $event->getId());
                if (Mage::helper('event')->getGeneralConfig('router', Mage::app()->getStore()->getStoreId()) != '') {
                    $urlRewrite->setRequestPath(Mage::helper('event')->getGeneralConfig('router', Mage::app()->getStore()->getStoreId()) . '/' . $event->getUrlKey());
                } else {
                    $urlRewrite->setRequestPath('event/' . $event->getUrlKey());
                }
                $urlRewrite->setTargetPath('event/index/view/id/' . $event->getId());
                $urlRewrite->setIsSystem(1);
                $urlRewrite->save();
            }
        } catch (Exception $e) {
            
        }
    }

}
