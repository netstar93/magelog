<?php

class MGS_Mpanel_Model_Source_Poll {

    public function toOptionArray() {
        $arr = array(array('value' => '', 'label' => ''));
        $polls = Mage::getModel('poll/poll')->getCollection()
                ->addFieldToFilter('closed', array('eq' => 0));
        if (count($polls) > 0) {
            foreach ($polls as $poll) {
                $arr[] = array(
                    'value' => $poll->getData('poll_id'),
                    'label' => $poll->getData('poll_title')
                );
            }
        }
        return $arr;
    }

}
