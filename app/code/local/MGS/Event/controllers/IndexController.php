<?php

/* * ****************************************************
 * Package   : Event
 * Author    : HIEPNH
 * Copyright : (c) 2014
 * ***************************************************** */
?>
<?php

class MGS_Event_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Event'));
        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbs->addCrumb('home', array(
                'label' => $this->__('Home'),
                'title' => $this->__('Go to Home Page'),
                'link' => Mage::getBaseUrl()
            ))->addCrumb('event-list', array(
                'label' => $this->__('Event'),
                'title' => $this->__('Event')
            ));
        }
        $this->renderLayout();
    }

    public function viewAction() {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('event/event')->load($id);
        if ($model->getId() == null || $model->getId() == '') {
            $this->norouteAction();
            return;
        }
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__($model->getTitle()));
        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbs->addCrumb('home', array(
                'label' => $this->__('Home'),
                'title' => $this->__('Go to Home Page'),
                'link' => Mage::getBaseUrl()
            ))->addCrumb('event-list', array(
                'label' => $this->__('Event'),
                'title' => $this->__('Event'),
                'link' => Mage::getUrl('event')
            ))->addCrumb('event-view', array(
                'label' => $this->__($model->getTitle()),
                'title' => $this->__($model->getTitle())
            ));
        }
        $this->renderLayout();
    }

}
