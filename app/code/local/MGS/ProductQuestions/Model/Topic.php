<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Model_Topic extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('productquestions/topic');
    }

    public function formatUrl($url) {
        return Mage::getModel('catalog/product_url')->formatUrlKey($url);
    }

    public function getOptionArray() {
        $topics = array();
        $topics[] = array('value' => '', 'label' => '');
        $collection = Mage::getModel('productquestions/topic')->getCollection();
        $collection->setOrder('title', 'ASC');
        foreach ($collection as $topic) {
            $topics[] = array('value' => $topic->getId(), 'label' => $topic->getTitle());
        }
        return $topics;
    }

    public function toOptionArray() {
        $options = array();
        $collection = Mage::getModel('productquestions/topic')->getCollection();
        $collection->setOrder('title', 'ASC');
        foreach ($collection as $topic) {
            $options[$topic->getId()] = Mage::helper('productquestions')->__($topic->getTitle());
        }

        return $options;
    }

    public function hasChildTopic() {
        $topics = Mage::getModel('productquestions/topic')->getCollection()
                ->addFieldToFilter('parent_id', $this->getId())
                ->addFieldToFilter('show_on_block', 1)
                ->addFieldToFilter('status', 1)
                ->setOrder('title', 'ASC');
        if (count($topics)) {
            return true;
        } else {
            return false;
        }
    }

    public function haveQuestions() {
        $topics = Mage::getModel('productquestions/question')->getCollection()
                ->addFieldToFilter('topic_id', $this->getId())
                ->addFieldToFilter('status', 2)
                ->setOrder('content', 'ASC');
        if (count($topics)) {
            return true;
        } else {
            return false;
        }
    }

}
