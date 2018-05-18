<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Adminhtml_AnswerController extends Mage_Adminhtml_Controller_Action {

    public function newAction() {
        $this->loadLayout('empty')->renderLayout();
    }

    public function editAction() {
        $this->loadLayout('empty')->renderLayout();
    }

    public function saveAction() {
        $response = array();
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('productquestions/answer');
            $model->setData($data)
                    ->setId($this->getRequest()->getParam('id'));
            try {
                $model->save();
                $answer = Mage::getModel('productquestions/answer')->load($model->getId());
                if ($answer->getACreatedAt() == NULL || $answer->getAUpdatedAt() == NULL) {
                    $answer->setACreatedAt(now())
                            ->setAUpdatedAt(now());
                } else {
                    $answer->setAUpdatedAt(now());
                }
                $answer->save();
                if ((int) $data['a_status'] == 2) {
                    if (Mage::helper('productquestions')->answerTemplate()) {
                        $question = Mage::getModel('productquestions/question')->load($answer->getQuestionId());
                        $product = Mage::getModel('catalog/product')->load($question->getProductId());
                        $template = Mage::getModel('core/email_template')->load(Mage::helper('productquestions')->answerTemplate());
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
                        $recepientEmail = $answer->getACustomerEmail();
                        $recepientName = $answer->getACustomerName();
                        $storeId = Mage::app()->getStore()->getId();
                        $vars = array('customerName' => $answer->getACustomerName(),
                            'questionContent' => $question->getContent(),
                            'answerContent' => $answer->getAContent(),
                            'productUrl' => Mage::getBaseUrl() . $product->getUrlKey() . '.html',
                            'productName' => $product->getName());
                        $translate = Mage::getSingleton('core/translate');
                        Mage::getModel('core/email_template')
                                ->sendTransactional($templateId, $sender, $recepientEmail, $recepientName, $vars, $storeId);
                        $translate->setTranslateInline(true);
                    }
                }
                $response['message'] = 'SUCCESS';
                $this->getResponse()->setBody(json_encode($response));
            } catch (Exception $e) {
                $response['message'] = 'ERROR';
                $this->getResponse()->setBody(json_encode($response));
            }
        }
    }

    public function deleteAction() {
        $response = array();
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('productquestions/answer');
                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();
                $response['message'] = 'SUCCESS';
                $this->getResponse()->setBody(json_encode($response));
            } catch (Exception $e) {
                $response['message'] = 'ERROR';
                $this->getResponse()->setBody(json_encode($response));
            }
        }
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
