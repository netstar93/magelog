<?php

/* * ****************************************************
 * Package   : ProductQuestions
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_ProductQuestions_Helper_Data extends MGS_Mgscore_Helper_Data {

    public function isActive() {
        return Mage::getStoreConfig('productquestions/general/active');
    }

    public function allowGuestToAskQuestion() {
        return Mage::getStoreConfig('productquestions/general/who_ask');
    }

    public function allowCustomerToAddAnswer() {
        return Mage::getStoreConfig('productquestions/general/who_answer');
    }

    public function approveQuestionAutomatic() {
        return Mage::getStoreConfig('productquestions/general/automatic');
    }

    public function allowRate() {
        return Mage::getStoreConfig('productquestions/general/rate');
    }

    public function allowGuestRateHelpfulness() {
        return Mage::getStoreConfig('productquestions/general/who_rate');
    }

    public function allowCustomerDefinedQuestionVisibility() {
        return Mage::getStoreConfig('productquestions/general/visibility');
    }

    public function activeQuestionEmail() {
        return Mage::getStoreConfig('productquestions/question_email/active');
    }

    public function notificationToAdmin() {
        return Mage::getStoreConfig('productquestions/question_email/notification');
    }

    public function adminEmail() {
        return Mage::getStoreConfig('productquestions/question_email/admin_email');
    }

    public function emailSender() {
        return Mage::getStoreConfig('productquestions/question_email/email_sender');
    }

    public function adminQuestionTemplate() {
        return Mage::getStoreConfig('productquestions/question_email/admin_question_template');
    }

    public function adminAnswerTemplate() {
        return Mage::getStoreConfig('productquestions/question_email/admin_answer_template');
    }

    public function questionTemplate() {
        return Mage::getStoreConfig('productquestions/question_email/question_template');
    }

    public function answerTemplate() {
        return Mage::getStoreConfig('productquestions/question_email/answer_template');
    }

    public function faqsPageTitle() {
        return Mage::getStoreConfig('productquestions/faqs_page/title');
    }

    public function faqsPageUrlKey() {
        return Mage::getStoreConfig('productquestions/faqs_page/url_key');
    }

    public function faqsPageUrl() {
        return Mage::getUrl(Mage::getStoreConfig('productquestions/faqs_page/url_key'));
    }

    public function toToplink() {
        return Mage::getStoreConfig('productquestions/faqs_page/faqs_link_to_toplink');
    }

    public function faqsPageMetaKeywords() {
        return Mage::getStoreConfig('productquestions/faqs_page/meta_keywords');
    }

    public function faqsPageMetaDescription() {
        return Mage::getStoreConfig('productquestions/faqs_page/meta_description');
    }

    public function numberOfQuestionsPerPage() {
        return Mage::getStoreConfig('productquestions/faqs_page/number_of_questions_per_page');
    }

    public function allowAccordition() {
        return Mage::getStoreConfig('productquestions/faqs_page/accordition');
    }

    public function sortBy() {
        return Mage::getStoreConfig('productquestions/faqs_page/sort_by');
    }

    public function activeFaqsBlock() {
        return Mage::getStoreConfig('productquestions/faqs_block/active');
    }

    public function blockTitle() {
        return Mage::getStoreConfig('productquestions/faqs_block/block_title');
    }

    public function numberOfTopics() {
        return Mage::getStoreConfig('productquestions/faqs_block/number_of_topics');
    }    

    public function enabledReCaptcha() {
        return Mage::getStoreConfig('productquestions/recaptcha/enabled');
    }

    public function getPublicKey() {
        return Mage::getStoreConfig('productquestions/recaptcha/public_key');
    }

    public function getPrivateKey() {
        return Mage::getStoreConfig('productquestions/recaptcha/private_key');
    }

    public function getTheme() {
        return Mage::getStoreConfig('productquestions/recaptcha/theme');
    }

    public function getLang() {
        return Mage::getStoreConfig('productquestions/recaptcha/lang');
    }

}
