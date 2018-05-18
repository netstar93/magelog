<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Adminhtml_QuestionController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('mgscore/productquestions')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Questions Manager'), Mage::helper('adminhtml')->__('Question Manager'));
        return $this;
    }

    public function indexAction() {
        $this->_title($this->__('Mage Solutions'))
                ->_title($this->__('Product Questions'))
                ->_title($this->__('Manage Questions'));
        $this->_initAction()
                ->renderLayout();
    }

    public function gridAction() {
        $this->getResponse()->setBody(
                $this->getLayout()->createBlock('productquestions/adminhtml_question_grid')->toHtml()
        );
    }

    public function answerGridAction() {
        $this->loadLayout();
        $this->getLayout()->getBlock('question.edit.tab.answer');
        $this->renderLayout();
    }

    public function sharingAction() {
        $this->loadLayout();
        $this->getLayout()->getBlock('question.edit.tab.sharing')
                ->setProductIds($this->getRequest()->getPost('product_ids', null));
        $this->renderLayout();
    }

    public function sharingGridAction() {
        $this->loadLayout();
        $this->getLayout()->getBlock('question.edit.tab.sharing')
                ->setProductIds($this->getRequest()->getPost('product_ids', null));
        $this->renderLayout();
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            $this->_title($this->__('Mage Solutions'))
                    ->_title($this->__('Product Questions'))
                    ->_title($this->__('Edit Question'));
        } else {
            $this->_title($this->__('Mage Solutions'))
                    ->_title($this->__('Product Questions'))
                    ->_title($this->__('New Question'));
        }
        $model = Mage::getModel('productquestions/question')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('question_data', $model);
            $this->loadLayout();
            $this->_setActiveMenu('mgscore/productquestions');
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Question Manager'), Mage::helper('adminhtml')->__('Question Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Question News'), Mage::helper('adminhtml')->__('Question News'));
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('productquestions/adminhtml_question_edit'))
                    ->_addLeft($this->getLayout()->createBlock('productquestions/adminhtml_question_edit_tabs'));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('productquestions')->__('Question does not exist.'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('productquestions/question');
            $model->setData($data)
                    ->setId($this->getRequest()->getParam('id'));
            try {
                $product = Mage::getModel('catalog/product')->load($data['product_id']);
                $model->setProductName($product->getName());
                $model->save();
                $question = Mage::getModel('productquestions/question')->load($model->getId());
                if ($question->getCreatedAt() == NULL || $question->getUpdatedAt() == NULL) {
                    $question->setCreatedAt(now())
                            ->setUpdatedAt(now());
                } else {
                    $question->setUpdatedAt(now());
                }
                $question->save();
                if ((int) $data['status'] == 2) {
                    if (Mage::helper('productquestions')->questionTemplate()) {
                        $template = Mage::getModel('core/email_template')->load(Mage::helper('productquestions')->questionTemplate());
                        $templateId = $template->getId();
                        if (Mage::helper('productquestions')->emailSender() == 'general') {
                            $senderName = Mage::getStoreConfig('trans_email/ident_general/name');
                            $senderEmail = Mage::getStoreConfig('trans_email/ident_general/email');
                        } elseif (Mage::helper('productquestions')->emailSender() == 'sales') {
                            $senderName = Mage::getStoreConfig('trans_email/ident_sales/name');
                            $senderEmail = Mage::getStoreConfig('trans_email/ident_sales/email');
                        } elseif (Mage::helper('productquestions')->emailSender() == 'custom1') {
                            $senderName = Mage::getStoreConfig('trans_email/ident_custom1/name');
                            $senderEmail = Mage::getStoreConfig('trans_email/ident_custom1/email');
                        } elseif (Mage::helper('productquestions')->emailSender() == 'custom2') {
                            $senderName = Mage::getStoreConfig('trans_email/ident_custom2/name');
                            $senderEmail = Mage::getStoreConfig('trans_email/ident_custom2/email');
                        } else {
                            $senderName = Mage::getStoreConfig('trans_email/ident_support/name');
                            $senderEmail = Mage::getStoreConfig('trans_email/ident_support/email');
                        }
                        $sender = array('name' => $senderName,
                            'email' => $senderEmail);
                        $recepientEmail = $question->getCustomerEmail();
                        $recepientName = $question->getCustomerName();
                        $storeId = Mage::app()->getStore()->getId();
                        $vars = array('customerName' => $question->getCustomerName(),
                            'questionContent' => $question->getContent(),
                            'productUrl' => Mage::getBaseUrl() . $product->getUrlKey() . '.html',
                            'productName' => $product->getName());
                        $translate = Mage::getSingleton('core/translate');
                        Mage::getModel('core/email_template')
                                ->sendTransactional($templateId, $sender, $recepientEmail, $recepientName, $vars, $storeId);
                        $translate->setTranslateInline(true);
                    }
                }
                if (isset($data['question']['product_ids']) && ($data['question']['product_ids'] != '' || $data['question']['product_ids'] != null)) {
                    $decode = Mage::helper('adminhtml/js')->decodeGridSerializedInput($data['question']['product_ids']);
                    $productIds = array();
                    foreach ($decode as $key => $value) {
                        $productIds[] = $key;
                    }
                    $sharings = Mage::getModel('productquestions/sharing')->getCollection()
                            ->addFieldToFilter('question_id', array('eq' => $model->getId()));
                    foreach ($sharings as $sharing) {
                        $s = Mage::getModel('productquestions/sharing')->load($sharing->getId());
                        $s->delete();
                    }
                    foreach ($productIds as $productId) {
                        if ((int) $productId != (int) $model->getProductId()) {
                            $s = Mage::getModel('productquestions/sharing');
                            $s->setQuestionId($model->getId());
                            $s->setProductId($productId);
                            $s->save();
                        }
                    }
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('productquestions')->__('Question was successfully saved.'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('productquestions')->__('Unable to find question to save.'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('productquestions/question');
                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();
                $sharingCollection = Mage::getModel('productquestions/sharing')->getCollection()
                        ->addFieldToFilter('question_id', array('eq' => $this->getRequest()->getParam('id')));
                foreach ($sharingCollection as $sharing) {
                    $sharing->delete();
                }
                $answerCollection = Mage::getModel('productquestions/answer')->getCollection()
                        ->addFieldToFilter('question_id', array('eq' => $this->getRequest()->getParam('id')));
                foreach ($answerCollection as $answer) {
                    $answer->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Question was successfully deleted.'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $questionIds = $this->getRequest()->getParam('questions');
        if (!is_array($questionIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($questionIds as $questionId) {
                    $question = Mage::getModel('productquestions/question')->load($questionId);
                    $sharingCollection = Mage::getModel('productquestions/sharing')->getCollection()
                            ->addFieldToFilter('question_id', array('eq' => $question->getId()));
                    foreach ($sharingCollection as $sharing) {
                        $sharing->delete();
                    }
                    $answerCollection = Mage::getModel('productquestions/answer')->getCollection()
                            ->addFieldToFilter('question_id', array('eq' => $question->getId()));
                    foreach ($answerCollection as $answer) {
                        $answer->delete();
                    }
                    $question->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted.', count($questionIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $questionIds = $this->getRequest()->getParam('questions');
        if (!is_array($questionIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($questionIds as $questionId) {
                    $question = Mage::getSingleton('productquestions/question')
                            ->load($questionId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                    $product = Mage::getModel('catalog/product')->load($question->getProductId());
                    if ((int) $this->getRequest()->getParam('status') == 2) {
                        if (Mage::helper('productquestions')->questionTemplate()) {
                            $template = Mage::getModel('core/email_template')->load(Mage::helper('productquestions')->questionTemplate());
                            $templateId = $template->getId();
                            if (Mage::helper('productquestions')->emailSender() == 'general') {
                                $senderName = Mage::getStoreConfig('trans_email/ident_general/name');
                                $senderEmail = Mage::getStoreConfig('trans_email/ident_general/email');
                            } elseif (Mage::helper('productquestions')->emailSender() == 'sales') {
                                $senderName = Mage::getStoreConfig('trans_email/ident_sales/name');
                                $senderEmail = Mage::getStoreConfig('trans_email/ident_sales/email');
                            } elseif (Mage::helper('productquestions')->emailSender() == 'custom1') {
                                $senderName = Mage::getStoreConfig('trans_email/ident_custom1/name');
                                $senderEmail = Mage::getStoreConfig('trans_email/ident_custom1/email');
                            } elseif (Mage::helper('productquestions')->emailSender() == 'custom2') {
                                $senderName = Mage::getStoreConfig('trans_email/ident_custom2/name');
                                $senderEmail = Mage::getStoreConfig('trans_email/ident_custom2/email');
                            } else {
                                $senderName = Mage::getStoreConfig('trans_email/ident_support/name');
                                $senderEmail = Mage::getStoreConfig('trans_email/ident_support/email');
                            }
                            $sender = array('name' => $senderName,
                                'email' => $senderEmail);
                            $recepientEmail = $question->getCustomerEmail();
                            $recepientName = $question->getCustomerName();
                            $storeId = Mage::app()->getStore()->getId();
                            $vars = array('customerName' => $question->getCustomerName(),
                                'questionContent' => $question->getContent(),
                                'productUrl' => Mage::getBaseUrl() . $product->getUrlKey() . '.html',
                                'productName' => $product->getName());
                            $translate = Mage::getSingleton('core/translate');
                            Mage::getModel('core/email_template')
                                    ->sendTransactional($templateId, $sender, $recepientEmail, $recepientName, $vars, $storeId);
                            $translate->setTranslateInline(true);
                        }
                    }
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($questionIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction() {
        $fileName = 'questions.csv';
        $content = $this->getLayout()->createBlock('productquestions/adminhtml_question_grid')
                ->getCsv();
        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction() {
        $fileName = 'questions.xml';
        $content = $this->getLayout()->createBlock('productquestions/adminhtml_question_grid')
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
