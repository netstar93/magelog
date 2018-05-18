<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Adminhtml_TopicController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('mgscore/productquestions')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Topics Manager'), Mage::helper('adminhtml')->__('Topic Manager'));
        return $this;
    }

    public function indexAction() {
        $this->_title($this->__('Mage Solutions'))
                ->_title($this->__('Product Questions'))
                ->_title($this->__('Manage Topics'));
        $this->_initAction()
                ->renderLayout();
    }

    public function gridAction() {
        $this->getResponse()->setBody(
                $this->getLayout()->createBlock('productquestions/adminhtml_topic_grid')->toHtml()
        );
    }

    public function questionAction() {
        $this->loadLayout();
        $this->getLayout()->getBlock('topic.edit.tab.question')
                ->setQuestionIds($this->getRequest()->getPost('question_ids', null));
        $this->renderLayout();
    }

    public function questionGridAction() {
        $this->loadLayout();
        $this->getLayout()->getBlock('topic.edit.tab.question')
                ->setQuestionIds($this->getRequest()->getPost('question_ids', null));
        $this->renderLayout();
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            $this->_title($this->__('Mage Solutions'))
                    ->_title($this->__('Product Questions'))
                    ->_title($this->__('Edit Topic'));
        } else {
            $this->_title($this->__('Mage Solutions'))
                    ->_title($this->__('Product Questions'))
                    ->_title($this->__('New Topic'));
        }
        $model = Mage::getModel('productquestions/topic')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('topic_data', $model);
            $this->loadLayout();
            $this->_setActiveMenu('mgscore/productquestions');
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Topic Manager'), Mage::helper('adminhtml')->__('Topic Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Topic News'), Mage::helper('adminhtml')->__('Topic News'));
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('productquestions/adminhtml_topic_edit'))
                    ->_addLeft($this->getLayout()->createBlock('productquestions/adminhtml_topic_edit_tabs'));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('productquestions')->__('Topic does not exist.'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
            if ($data['identifier'] == '') {
                $data['identifier'] = Mage::getModel('productquestions/topic')->formatUrl($data['title']);
            }
            if($this->getRequest()->getParam('id')) {
                $model = Mage::getModel('productquestions/topic')->load($this->getRequest()->getParam('id'));
                $model->setTitle($data['title']);
                $model->setIdentifier($data['identifier']);
                $model->setShowOnBlock($data['show_on_block']);
				if(isset($data['store_id'])){
					$storeId = $data['store_id'];
				}else{
					$storeId = 0;
				}
                $model->setStoreId($storeId);                
                $model->setStatus($data['status']);                
            } else {
                $model = Mage::getModel('productquestions/topic');
                $model->setTitle($data['title']);
                $model->setIdentifier($data['identifier']);
                $model->setShowOnBlock($data['show_on_block']);
				if(isset($data['store_id'])){
					$storeId = $data['store_id'];
				}else{
					$storeId = 0;
				}
                $model->setStoreId($storeId);
                $model->setStatus($data['status']);
            }
            try {
                $model->save();
                if (isset($data['topic']['question_ids']) && ($data['topic']['question_ids'] != '' || $data['topic']['question_ids'] != null)) {
                    $decode = Mage::helper('adminhtml/js')->decodeGridSerializedInput($data['topic']['question_ids']);
                    $questions = Mage::getModel('productquestions/question')->getCollection()
                            ->addFieldToFilter('topic_id', array('eq' => $model->getId()));
                    foreach ($questions as $question) {
                        $q = Mage::getModel('productquestions/question')->load($question->getId());
                        $q->setTopicId('NULL');
                        $q->save();
                    }
                    foreach ($decode as $key => $value) {
                        $question = Mage::getModel('productquestions/question')->load($key);
                        $question->setTopicId($model->getId());
                        $question->save();
                    }
                } else {
                    $questions = Mage::getModel('productquestions/question')->getCollection()
                            ->addFieldToFilter('topic_id', array('eq' => $model->getId()));
                    foreach ($questions as $question) {
                        $q = Mage::getModel('productquestions/question')->load($question->getId());
                        $q->setTopicId('NULL');
                        $q->save();
                    }
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('productquestions')->__('Topic was successfully saved.'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('productquestions')->__('Unable to find topic to save.'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('productquestions/topic');
                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Topic was successfully deleted.'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $topicIds = $this->getRequest()->getParam('topics');
        if (!is_array($topicIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($topicIds as $topicId) {
                    $topic = Mage::getModel('productquestions/topic')->load($topicId);
                    $topic->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted.', count($topicIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $topicIds = $this->getRequest()->getParam('topics');
        if (!is_array($topicIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                if ((int) $this->getRequest()->getParam('status') == 2) {
                    $value = '0';
                } else {
                    $value = $this->getRequest()->getParam('status');
                }
                foreach ($topicIds as $topicId) {
                    $topic = Mage::getSingleton('productquestions/topic')
                            ->load($topicId)
                            ->setStatus($value)
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($topicIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction() {
        $fileName = 'topics.csv';
        $content = $this->getLayout()->createBlock('productquestions/adminhtml_topic_grid')
                ->getCsv();
        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction() {

        $fileName = 'topics.xml';
        $content = $this->getLayout()->createBlock('productquestions/adminhtml_topic_grid')
                ->getXml();
        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType = 'application/octet-stream') {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }

    public function denyAction() {
        $this->_title($this->__('Mage Solutions'))
                ->_title($this->__('Product Questions'))
                ->_title($this->__('Access Denied'));

        $this->_initAction()
                ->_setActiveMenu('mgscore/productquestions')
                ->_addBreadcrumb(Mage::helper('productquestions')->__('Access Denied'), Mage::helper('productquestions')->__('Access Denied'));
        $enabled = Mage::app()->getRequest()->getParam('enabled');
        if ($enabled == 0) {
            $str = 'Incorrect license key.';
        } else if ($enabled == 2) {
            $str = 'License key for this extension has expired.';
        }
        if (isset($str) && $str != null) {
            $block = $this->getLayout()
                    ->createBlock('core/text', 'access-denied-block')
                    ->setText('<h2>' . $str . '</h2>');

            $this->_addContent($block);
        } else {
            $block = $this->getLayout()
                    ->createBlock('core/text', 'access-denied-block');

            $this->_addContent($block);
        }
        $this->renderLayout();
    }

}
