<?php

class MGS_Mpanel_Block_Head extends Mage_Core_Block_Template {

    protected function _prepareLayout() {
        if (Mage::getStoreConfig('mpanel/general/enabled')) {
            $headBlock = $this->getLayout()->getBlock('head');
            $setting = $this->helper('mpanel')->getThemeSettings();
            $fonts = array();
            $fonts[] = $setting['font'];

            if (!in_array($setting['h1'], $fonts)) {
                $fonts[] = $setting['h1'];
            }

            if (!in_array($setting['h2'], $fonts)) {
                $fonts[] = $setting['h2'];
            }

            if (!in_array($setting['h3'], $fonts)) {
                $fonts[] = $setting['h3'];
            }

            if (!in_array($setting['price'], $fonts)) {
                $fonts[] = $setting['price'];
            }

            if (!in_array($setting['menu'], $fonts)) {
                $fonts[] = $setting['menu'];
            }

            $links = '';
            $isSecure = Mage::app()->getStore()->isCurrentlySecure();
            foreach ($fonts as $_font) {
                if ($isSecure) {
                    $links .= '<link href="https://fonts.googleapis.com/css?family=' . $_font . ':200,300,400,500,600,700" rel="stylesheet" type="text/css"/>';
                } else {
                    $links .= '<link href="//fonts.googleapis.com/css?family=' . $_font . ':200,300,400,500,600,700" rel="stylesheet" type="text/css"/>';
                }
            }

            if ($setting['custom_css']) {
                $headBlock->addCss('css/custom.css');
            }

            $headBlock->addLinkRel('search', '/catalogsearch/advanced/index" />' . $links . '
			<link rel="stylesheet" type="text/css" media="screen" href="' . Mage::getUrl('mpanel/index/style', array('_secure' => true)) . '"/>
			<link rel="author" href="' . Mage::getBaseUrl() . '');
            return parent::_prepareLayout();
        }
    }

}
