<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        $action = $this->getRequest()->getActionName();
        $loginUrl = Mage::helper('customer')->getLoginUrl();

        if (!Mage::getSingleton('customer/session')->authenticate($this, $loginUrl)) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
        }
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Product Questions'));
        if ($block = $this->getLayout()->getBlock('customer.account.link.back')) {
            $block->setRefererUrl($this->_getRefererUrl());
        }
        $this->renderLayout();
    }

    public function saveAction() {
        $response = array();
        if ($data = $this->getRequest()->getPost()) {
			$storeId = Mage::app()->getStore()->getId();
			$data['store_id'] = $storeId;
            $model = Mage::getModel('productquestions/question');
            $model->setData($data)
                    ->setId($this->getRequest()->getParam('id'));
            try {
                if (Mage::helper('productquestions')->enabledReCaptcha()) {
                    require_once(Mage::getBaseDir('lib') . DS . 'reCaptcha' . DS . 'recaptchalib.php');
                    $privateKey = Mage::helper('productquestions')->getPrivateKey();
                    $remoteAddr = $this->getRequest()->getServer('REMOTE_ADDR');
                    $captcha = recaptcha_check_answer($privateKey, $remoteAddr, $data['recaptcha_challenge_field'], $data['recaptcha_response_field']);
                    if (!$captcha->is_valid) {
                        $response['message'] = 'ERROR';
                        $response['error'] = $this->__("The reCAPTCHA wasn't entered correctly. Try it again.");
                        $this->getResponse()->setBody(json_encode($response));
                        return;
                    }
                }
                if ($model->getCreatedAt() == NULL || $model->getUpdatedAt() == NULL) {
                    $model->setCreatedAt(now())
                            ->setUpdatedAt(now());
                } else {
                    $model->setUpdatedAt(now());
                }
				
				if(Mage::helper('productquestions')->approveQuestionAutomatic()){
					$model->setStatus(2);
				}
				
                $product = Mage::getModel('catalog/product')->load($data['product_id']);
                $model->setProductName($product->getName());
                $model->save();
                if (Mage::helper('productquestions')->activeQuestionEmail()) {
                    if (Mage::helper('productquestions')->notificationToAdmin()) {
                        if (Mage::helper('productquestions')->adminQuestionTemplate()) {
                            $template = Mage::getModel('core/email_template')->load(Mage::helper('productquestions')->adminQuestionTemplate());
                            $templateId = $template->getId();
                            $senderName = $model->getCustomerName();
                            $senderEmail = $model->getCustomerEmail();
                            $sender = array('name' => $senderName,
                                'email' => $senderEmail);
                            $recepientEmail = Mage::helper('productquestions')->adminEmail();
                            $users = Mage::getModel('admin/user')->getCollection()->addFieldToFilter('email', array('eq' => $recepientEmail));
                            if (count($users)) {
                                $user = $users->getFirstItem();
                                $recepientName = $user->getFirstname() . ' ' . $user->getLastname();
                            } else {
                                $recepientName = Mage::getStoreConfig('trans_email/ident_support/name');
                            }
                            
                            $vars = array('adminName' => $recepientName,
                                'customerName' => $model->getCustomerName(),
                                'customerEmail' => $model->getCustomerEmail(),
                                'questionContent' => $model->getContent(),
                                'productUrl' => $product->getProductUrl(),
                                'productName' => $product->getName());
                            $translate = Mage::getSingleton('core/translate');
                            Mage::getModel('core/email_template')
                                    ->sendTransactional($templateId, $sender, $recepientEmail, $recepientName, $vars, $storeId);
                            $translate->setTranslateInline(true);
                        }
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

    public function saveAnswerAction() {
        $response = array();
        $params = array();
        if ($data = $this->getRequest()->getPost()) {
            $params['question_id'] = $data['question_id'];
            $params['a_customer_name'] = $data['a_customer_name_' . $data['question_id']];
            $params['a_customer_email'] = $data['a_customer_email_' . $data['question_id']];
            $params['a_content'] = $data['a_content_' . $data['question_id']];
            $model = Mage::getModel('productquestions/answer');
            $model->setData($params)
                    ->setId($this->getRequest()->getParam('id'));
			
			if(Mage::helper('productquestions')->approveQuestionAutomatic()){
				$model->setAStatus(2);
			}
            try {
                if (Mage::helper('productquestions')->enabledReCaptcha()) {
                    require_once(Mage::getBaseDir('lib') . DS . 'reCaptcha' . DS . 'recaptchalib.php');
                    $privateKey = Mage::helper('productquestions')->getPrivateKey();
                    $remoteAddr = $this->getRequest()->getServer('REMOTE_ADDR');
                    $captcha = recaptcha_check_answer($privateKey, $remoteAddr, $data['recaptcha_challenge_field'], $data['recaptcha_response_field']);
                    if (!$captcha->is_valid) {
                        $response['message'] = 'ERROR';
                        $response['error'] = $this->__("The reCAPTCHA wasn't entered correctly. Try it again.");
                        $this->getResponse()->setBody(json_encode($response));
                        return;
                    }
                }
                if ($model->getACreatedAt() == NULL || $model->getAUpdatedAt() == NULL) {
                    $model->setACreatedAt(now())
                            ->setAUpdatedAt(now());
                } else {
                    $model->setAUpdatedAt(now());
                }
                $model->save();
                $question = Mage::getModel('productquestions/question')->load($model->getQuestionId());
                $product = Mage::getModel('catalog/product')->load($question->getProductId());
                if (Mage::helper('productquestions')->activeQuestionEmail()) {
                    if (Mage::helper('productquestions')->notificationToAdmin()) {
                        if (Mage::helper('productquestions')->adminAnswerTemplate()) {
                            $template = Mage::getModel('core/email_template')->load(Mage::helper('productquestions')->adminAnswerTemplate());
                            $templateId = $template->getId();
                            $senderName = $model->getACustomerName();
                            $senderEmail = $model->getACustomerEmail();
                            $sender = array('name' => $senderName,
                                'email' => $senderEmail);
                            $recepientEmail = Mage::helper('productquestions')->adminEmail();
                            $users = Mage::getModel('admin/user')->getCollection()->addFieldToFilter('email', array('eq' => $recepientEmail));
                            if (count($users)) {
                                $user = $users->getFirstItem();
                                $recepientName = $user->getFirstname() . ' ' . $user->getLastname();
                            } else {
                                $recepientName = Mage::getStoreConfig('trans_email/ident_support/name');
                            }
                            $storeId = Mage::app()->getStore()->getId();
                            $vars = array('adminName' => $recepientName,
                                'customerName' => $model->getACustomerName(),
                                'customerEmail' => $model->getACustomerEmail(),
                                'questionContent' => $question->getContent(),
                                'answerContent' => $model->getAContent(),
                                'productUrl' => $product->getProductUrl(),
                                'productName' => $product->getName());
                            $translate = Mage::getSingleton('core/translate');
                            Mage::getModel('core/email_template')
                                    ->sendTransactional($templateId, $sender, $recepientEmail, $recepientName, $vars, $storeId);
                            $translate->setTranslateInline(true);
                        }
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

    public function likeQuestionAction() {
        $response = array();
        if ($data = $this->getRequest()->getPost()) {
            $value = 1;
            $model = Mage::getModel('productquestions/question')->load($data['id']);
            $score = (int) $model->getScore() + $value;
            $model->setData('score', $score);
            try {
                $model->save();
                $response['message'] = 'SUCCESS';
                $response['question_like'] = 'liked';
                $response['score'] = $score;
                $this->getResponse()->setBody(json_encode($response));
            } catch (Exception $e) {
                $response['message'] = 'ERROR';
                $response['question_like'] = '';
                $this->getResponse()->setBody(json_encode($response));
            }
        }
    }

    public function dislikeQuestionAction() {
        $response = array();
        if ($data = $this->getRequest()->getPost()) {
            $value = -1;
            $model = Mage::getModel('productquestions/question')->load($data['id']);
            $score = (int) $model->getScore() + $value;
            $model->setData('score', $score);
            try {
                $model->save();
                $response['message'] = 'SUCCESS';
                $response['question_dislike'] = 'disliked';
                $response['score'] = $score;
                $this->getResponse()->setBody(json_encode($response));
            } catch (Exception $e) {
                $response['message'] = 'ERROR';
                $response['question_dislike'] = '';
                $this->getResponse()->setBody(json_encode($response));
            }
        }
    }

    public function likeAnswerAction() {
        $response = array();
        if ($data = $this->getRequest()->getPost()) {
            $value = 1;
            $model = Mage::getModel('productquestions/answer')->load($data['id']);
            $score = (int) $model->getAScore() + $value;
            $model->setData('a_score', $score);
            try {
                $model->save();
                $response['message'] = 'SUCCESS';
                $response['answer_like'] = 'liked';
                $response['score'] = $score;
                $this->getResponse()->setBody(json_encode($response));
            } catch (Exception $e) {
                $response['message'] = 'ERROR';
                $response['answer_like'] = '';
                $this->getResponse()->setBody(json_encode($response));
            }
        }
    }

    public function dislikeAnswerAction() {
        $response = array();
        if ($data = $this->getRequest()->getPost()) {
            $value = -1;
            $model = Mage::getModel('productquestions/answer')->load($data['id']);
            $score = (int) $model->getAScore() + $value;
            $model->setData('a_score', $score);
            try {
                $model->save();
                $response['message'] = 'SUCCESS';
                $response['answer_dislike'] = 'disliked';
                $response['score'] = $score;
                $this->getResponse()->setBody(json_encode($response));
            } catch (Exception $e) {
                $response['message'] = 'ERROR';
                $response['answer_dislike'] = '';
                $this->getResponse()->setBody(json_encode($response));
            }
        }
    }

}
