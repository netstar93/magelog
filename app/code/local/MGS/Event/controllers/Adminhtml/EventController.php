<?php

/* * ****************************************************
 * Package   : Event
 * Author    : HIEPNH
 * Copyright : (c) 2014
 * ***************************************************** */
?>
<?php

class MGS_Event_Adminhtml_EventController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->_title($this->__('MGS'))
                ->_title($this->__('Event'))
                ->_title($this->__('Manage Events'));
        $this->loadLayout()
                ->_setActiveMenu('mgscore/event/items')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('MGS'), Mage::helper('adminhtml')->__('MGS'))
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Events'), Mage::helper('adminhtml')->__('Manage Events'));
        return $this;
    }

    public function indexAction() {
        $this->_initAction()
                ->renderLayout();
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('event/event')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('event_data', $model);

            $this->_initAction();

            $this->_title($id ? $model->getTitle() : $this->__('New Event'));

            $item = $id ? Mage::helper('event')->__('Edit Event') : Mage::helper('catalog')->__('New Event');

            $this->_addBreadcrumb($item, $item);

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('event/adminhtml_event_edit'))
                    ->_addLeft($this->getLayout()->createBlock('event/adminhtml_event_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('event')->__('Item does not exist.'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
                try {
                    /* Starting upload */
                    $uploader = new Varien_File_Uploader('image');

                    // Any extention would work
                    $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png', 'JPG', 'JPEG', 'GIF', 'PNG'));
                    $uploader->setAllowRenameFiles(false);

                    // Set the file upload mode 
                    // false -> get the file directly in the specified folder
                    // true -> get the file in the product like folders 
                    //	(file.jpg will go in something like /media/f/i/file.jpg)
                    $uploader->setFilesDispersion(false);

                    // We set media as the upload dir
                    $path = Mage::getBaseDir('media') . DS . 'mgs' . DS . 'event' . DS;
                    $uploader->save($path, $_FILES['image']['name']);
                } catch (Exception $e) {
                    
                }

                //this way the name is saved in DB
                $data['image'] = 'mgs/event/' . str_replace(' ', '_', $_FILES['image']['name']);
            } else {
                if (isset($data['image']['delete']) && $data['image']['delete'] == 1) {
                    $data['image'] = '';
                } else {
                    unset($data['image']);
                }
            }

            if (!isset($data['url_key']) || $data['url_key'] == '') {
                $data['url_key'] = Mage::getModel('catalog/product_url')->formatUrlKey($data['title']);
            }

            $model = Mage::getModel('event/event');
            $model->setData($data)
                    ->setId($this->getRequest()->getParam('id'));

            try {
                $model->save();
                $urlRewriteCollection = Mage::getModel('core/url_rewrite')
                        ->getCollection()
                        ->addFieldToFilter('id_path', 'event/view/' . $model->getId());
                foreach ($urlRewriteCollection as $url) {
                    $url->delete();
                }
                $urlRewrite = Mage::getModel('core/url_rewrite')->loadByIdPath('event/view/' . $model->getId())
                        ->setIdPath('event/view/' . $model->getId());
                if (Mage::helper('event')->getGeneralConfig('router', Mage::app()->getStore()->getStoreId()) != '') {
                    $urlRewrite->setRequestPath(Mage::helper('event')->getGeneralConfig('router', Mage::app()->getStore()->getStoreId()) . '/' . $model->getUrlKey());
                } else {
                    $urlRewrite->setRequestPath('event/' . $model->getUrlKey());
                }
                $urlRewrite->setTargetPath('event/index/view/id/' . $model->getId());
                $urlRewrite->setIsSystem(1);
                $urlRewrite->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('event')->__('Item was successfully saved.'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('event')->__('Unable to find item to save.'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('event/event');

                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();
                $urlRewriteCollection = Mage::getModel('core/url_rewrite')
                        ->getCollection()
                        ->addFieldToFilter('id_path', 'event/view/' . $this->getRequest()->getParam('id'));
                foreach ($urlRewriteCollection as $url) {
                    $url->delete();
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted.'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $eventIds = $this->getRequest()->getParam('event');
        if (!is_array($eventIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($eventIds as $eventId) {
                    $event = Mage::getModel('event/event')->load($eventId);
                    $urlRewriteCollection = Mage::getModel('core/url_rewrite')
                            ->getCollection()
                            ->addFieldToFilter('id_path', 'event/view/' . $event->getId());
                    foreach ($urlRewriteCollection as $url) {
                        $url->delete();
                    }
                    $event->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted.', count($eventIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $eventIds = $this->getRequest()->getParam('event');
        if (!is_array($eventIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($eventIds as $eventId) {
                    $event = Mage::getSingleton('event/event')
                            ->load($eventId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated.', count($eventIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function refreshUrlAction() {
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
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('*/*/');
            return;
        }
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('event')->__('All urls were successfully rewrited.'));
        $this->_redirect('*/*/');
    }

}
