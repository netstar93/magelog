<?php

class MGS_Mpanel_IndexController extends Mage_Core_Controller_Front_Action {

    public function newAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function styleAction() {
        $this->getResponse()->setHeader('Content-type', 'text/css', true);
        $helper = Mage::helper('mpanel');
        $setting = $helper->getThemeSettings();
        $html = '';
        $fontName = Mage::getStoreConfig('mgs_theme/custom_style/font_name');
		$storeId = Mage::app()->getStore()->getId();
        $fontDir = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'mpanel/fonts/';

        $ttfFile = $fontDir . Mage::getStoreConfig('mgs_theme/custom_style/ttf');
        $eotFile = $fontDir . Mage::getStoreConfig('mgs_theme/custom_style/eot');
        $woffFile = $fontDir . Mage::getStoreConfig('mgs_theme/custom_style/woff');
        $svgFile = $fontDir . Mage::getStoreConfig('mgs_theme/custom_style/svg');

        if ($fontName != '') {
            if ($ttfFile != '' && $eotFile != '') {
                $html .= '@font-face {
								font-family: "' . $fontName . '";
								src: url("' . $eotFile . '");
								src: url("' . $eotFile . '?#iefix") format("embedded-opentype"),
									 url("' . $woffFile . '") format("woff"),
									 url("' . $ttfFile . '") format("truetype"),
									 url("' . $svgFile . '#' . $fontName . '") format("svg");
								font-weight: normal;
								font-style: normal;
						}
						';
            }
        }



        $html .= '
		body{
			background-color: ' . $setting['bg_color'] . ';';
        if ($setting['layout'] == 'boxed') {
            if ($setting['bg_upload'] != '') {
                $html .= 'background-image: url(' . Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'mpanel/background/' . $setting['bg_upload'] . ');';
            } else {
                if ($setting['bg_image'] != '') {
                    $html .= 'background-image: url(' . Mage::getDesign()->getSkinUrl('images/patterns/' . $setting['bg_image'] . '.png') . ');';
                }
            }

            $html .= 'background-repeat: ' . $setting['bg_repeat'] . ';
			background-position: ' . $setting['bg_position_x'] . ' ' . $setting['bg_position_y'] . ';';

            if (Mage::getStoreConfig('mgs_theme/fonts/font_size') != '') {
                $html .= 'font-size:' . Mage::getStoreConfig('mgs_theme/fonts/font_size') . ';';
            }
        }

        $html .= 'font-family: "' . str_replace('+', ' ', $setting['font']) . '", arial, tahoma;
			font-weight: normal;
		}';
		
		$custom_font = Mage::getStoreConfig('mgs_theme/fonts/custom_class', $storeId);
		
        $fontStyle = array(
            '#mainMenu li.level0 a, #mainMenu li.level0 a span, #mainMenu li.level1 a, #mainMenu li.level1 a span, #mainMenu li.level2 a, #mainMenu li.level2 a span, #menu-collapse a.level0' => array(
                'font-family' => '"' . str_replace('+', ' ', $setting['menu']) . '", arial, tahoma',
                'font-size' => Mage::getStoreConfig('mgs_theme/fonts/menu_fontsize'),
            ),
			'.nav-sub-menu li.level0 a, .nav-sub-menu li.level0 a span, .nav-sub-menu li.level1 a, .nav-sub-menu li.level1 a span, .nav-sub-menu li.level2 a, .nav-sub-menu li.level2 a span, #menu-button' => array(
                'font-family' => '"' . str_replace('+', ' ', $setting['menu']) . '", arial, tahoma',
                'font-size' => Mage::getStoreConfig('mgs_theme/fonts/menu_fontsize'),
            ),
            'h1, .page-title h1' => array(
                'font-family' => '"' . str_replace('+', ' ', $setting['h1']) . '", arial, tahoma',
                'font-size' => Mage::getStoreConfig('mgs_theme/fonts/h1_fontsize'),
            ),
            'h2, .page-title h2' => array(
                'font-family' => '"' . str_replace('+', ' ', $setting['h2']) . '", arial, tahoma',
                'font-size' => Mage::getStoreConfig('mgs_theme/fonts/h2_fontsize'),
            ),
            'h3, .page-title h3' => array(
                'font-family' => '"' . str_replace('+', ' ', $setting['h3']) . '", arial, tahoma',
                'font-size' => Mage::getStoreConfig('mgs_theme/fonts/h3_fontsize'),
            ),
            'h4' => array(
                'font-family' => '"' . str_replace('+', ' ', $setting['h4']) . '", arial, tahoma',
                'font-size' => Mage::getStoreConfig('mgs_theme/fonts/h4_fontsize'),
            ),
            'h5' => array(
                'font-family' => '"' . str_replace('+', ' ', $setting['h5']) . '", arial, tahoma',
                'font-size' => Mage::getStoreConfig('mgs_theme/fonts/h5_fontsize'),
            ),
            'h6' => array(
                'font-family' => '"' . str_replace('+', ' ', $setting['h6']) . '", arial, tahoma',
                'font-size' => Mage::getStoreConfig('mgs_theme/fonts/h6_fontsize'),
            ),
            '.price, .price-box .price' => array(
                'font-family' => '"' . str_replace('+', ' ', $setting['price']) . '", arial, tahoma',
                'font-size' => Mage::getStoreConfig('mgs_theme/fonts/price_fontsize'),
            ),
			$custom_font => array(
				'font-family' => str_replace('+', ' ', Mage::getStoreConfig('mgs_theme/fonts/custom_font', $storeId)),
				'font-size' => Mage::getStoreConfig('mgs_theme/fonts/custom_fontsize', $storeId),
			)
        );
        $fontStyle = array_filter($fontStyle);

        foreach ($fontStyle as $class => $style) {
            $style = array_filter($style);
            if (count($style) > 0) {
                $html .= $class . '{';
                foreach ($style as $_style => $value) {
                    $html .= $_style . ': ' . $value . ';';
                }
                $html .= '}
				';
            }
        }

        $mainColorSetting = $helper->getMainColorSetting();
        if ($helper->useMainCustomColor() && count($mainColorSetting) > 0) {
            foreach ($mainColorSetting as $class => $style) {
                $style = array_filter($style);
                if (count($style) > 0) {
                    $html .= $class . '{';
                    foreach ($style as $_style => $value) {
                        $html .= $_style . ': ' . $value . ';';
                    }
                    $html .= '}
					';
                }
            }
        }

        $headerColorSetting = $helper->getHeaderColorSetting();
        if ($helper->useHeaderCustomColor() && count($headerColorSetting) > 0) {
            foreach ($headerColorSetting as $class => $style) {
                $style = array_filter($style);
                if (count($style) > 0) {
                    $html .= $class . '{';
                    foreach ($style as $_style => $value) {
                        $html .= $_style . ': ' . $value . ' !important;';
                    }
                    $html .= '}
					';
                }
            }
        }

        $footerColorSetting = $helper->getFooterColorSetting();
        if ($helper->useFooterCustomColor() && count($footerColorSetting) > 0) {
            foreach ($footerColorSetting as $class => $style) {
                $style = array_filter($style);
                if (count($style) > 0) {
                    $html .= $class . '{';
                    foreach ($style as $_style => $value) {
                        $html .= $_style . ': ' . $value . ' !important;';
                    }
                    $html .= '}
					';
                }
            }
        }

        if (Mage::getStoreConfig('mgs_theme/custom_style/style') != '') {
            $html .= Mage::getStoreConfig('mgs_theme/custom_style/style');
        }

        $this->getResponse()->setBody($html);
    }

    public function newInCategoryAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function newInCmsAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function newInCustomerAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function newInCatalogSearchAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function pollAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function pollInCmsAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function pollInCustomerAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function pollInCatalogSearchAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function promoAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function promoInCmsAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function promoInCustomerAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function promoInCatalogSearchAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function afterAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function redirectInCustomerAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function redirectInCatalogSearchAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function formAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function formInCategoryAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function formInCmsAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function formInCustomerAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function formInCatalogSearchAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function createProductTabAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function manageProductTabsAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function createStaticBlockAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function categoryNavigationAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function categoryNavigationInCmsAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function categoryNavigationInCustomerAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function categoryNavigationInCatalogSearchAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function menuAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function featuredProductsAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function bestsellerProductsAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function newProductsAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function topRateProductsAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function saleProductsAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function facebookLikeBoxAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function twitterFeedAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function menuInCatalogSearchAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function featuredProductsInCatalogSearchAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function bestsellerProductsInCatalogSearchAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function newProductsInCatalogSearchAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function topRateProductsInCatalogSearchAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function saleProductsInCatalogSearchAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function facebookLikeBoxInCatalogSearchAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function twitterFeedInCatalogSearchAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function ajaxAction() {
        $helper = Mage::helper('mpanel');

        $storeId = Mage::app()->getStore()->getId();

        $config = Mage::getModel('mpanel/store')
                ->getCollection()
                ->addFieldToFilter('store_id', $storeId)
                ->addFieldToFilter('status', 1);

        $output = $this->getLayout()->createBlock('mpanel/template')->setTemplate('mgs/mpanel/template/admin/' . $config->getFirstItem()->getName() . '.phtml')->toHtml();

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array('html' => $output)));
    }

    public function newsectionAction() {
        $helper = Mage::helper('mpanel');

        $storeId = Mage::app()->getStore()->getId();

        $lastBlock = Mage::getModel('mpanel/blocks')->getCollection()->addFieldToFilter('store_id', $storeId)->setOrder('block_position', 'DESC')->getFirstItem();
        $lastPosition = $lastBlock->getBlockPosition() + 1;

        try {
            $block = Mage::getModel('mpanel/blocks');
            $block->setName('block')->setThemeName('1_column_full')->setStoreId($storeId)->setBlockPosition($lastPosition);
            $block->save();

            Mage::getModel('mpanel/blocks')->setName('block' . $block->getId())->setId($block->getId())->save();
        } catch (Exception $e) {
            echo $e->getMessage();
        }


        $config = Mage::getModel('mpanel/store')
                ->getCollection()
                ->addFieldToFilter('store_id', $storeId)
                ->addFieldToFilter('status', 1);

        $output = $this->getLayout()->createBlock('mpanel/template')->setTemplate('mgs/mpanel/template/admin/' . $config->getFirstItem()->getName() . '.phtml')->toHtml();

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array('html' => $output)));
    }

    public function deletesectionAction() {
        $id = $this->getRequest()->getParam('id');
        $helper = Mage::helper('mpanel');
        $storeId = Mage::app()->getStore()->getId();

        try {
			$blockName = Mage::getModel('mpanel/blocks')->load($id)->getName();
            Mage::getModel('mpanel/blocks')->setId($id)->delete();

            $blockId = 'block' . $id . '-';
            $childs = Mage::getModel('mpanel/childs')
                    ->getCollection()
                    ->addFieldToFilter('block_name', array('like' => $blockName . '%'))
                    ->addFieldToFilter('store_id', $storeId);

            if (count($childs) > 0) {
                foreach ($childs as $_child) {
                    $_child->delete();
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $config = Mage::getModel('mpanel/store')
                ->getCollection()
                ->addFieldToFilter('store_id', $storeId)
                ->addFieldToFilter('status', 1);

        $output = $this->getLayout()->createBlock('mpanel/template')->setTemplate('mgs/mpanel/template/admin/' . $config->getFirstItem()->getName() . '.phtml')->toHtml();

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array('html' => $output)));
    }

    public function sortsectionAction() {
        if ($data = $this->getRequest()->getPost()) {
            foreach ($data['block'] as $position => $blockId) {
                Mage::getModel('mpanel/blocks')->setBlockPosition($position)->setId($blockId)->save();
            }
        }
    }

    public function rightAction() {
        $categoryId = $this->getRequest()->getParam('category_id');
        $output = $this->getLayout()->createBlock('mpanel/template')->setTemplateLayout('category_right')->setBlockName('block_category_right')->setCategoryId($categoryId)->setTemplate('mgs/mpanel/template/admin/category_right.phtml')->toHtml();

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array('html' => $output)));
    }

    public function leftAction() {
        $categoryId = $this->getRequest()->getParam('category_id');
        $output = $this->getLayout()->createBlock('mpanel/template')->setTemplateLayout('category_left')->setBlockName('block_category_left')->setCategoryId($categoryId)->setTemplate('mgs/mpanel/template/admin/category_left.phtml')->toHtml();

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array('html' => $output)));
    }

    // Active Homepage Builder
    public function activeAction() {
        $referer = $this->getRequest()->getParam('referrer');
        $url = Mage::helper('core')->urlDecode($referer);
        Mage::getSingleton('core/session')->setUsePanel(1);
        $this->_redirectUrl($url);
        return;
    }

    // Deactivation Homepage Builder
    public function deactiveAction() {
        $referer = $this->getRequest()->getParam('referrer');
        $url = Mage::helper('core')->urlDecode($referer);
        Mage::getSingleton('core/session')->setUsePanel(false);
        $this->_redirectUrl($url);
        return;
    }

    public function bannerAction() {
        $bannerId = $this->getRequest()->getParam('id');
        $banner = Mage::getModel('promobanners/promobanners')->load($bannerId);

        $html = '<div class="promo-banner">
					<div class="banner-position">
						<div class="banner">
							<div class="text-content" id="text_temp">' . $banner->getContent() . '</div>';
        if ($banner->getButton() != '') {
            $html .= '<div class="button-content" id="button-container">
					<button class="btn btn-primary btn-promo-banner">' . $banner->getButton() . '</button>
				</div>';
        }

        $html .= '</div>
					</div>
					<img class="img-responsive" src="' . Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'promobanners/' . $banner->getFilename() . '" alt="" id="img_temp" style="width:100%"/>
					<div class="banner-hover">
						<a href="#">&nbsp;</a>
					</div>
				</div>';
        echo $html;
    }

    public function revolutionAction() {
        $id = $this->getRequest()->getParam('id');
        $slider = Mage::getModel('revslider/slider')->load($id);

        $bannerWidth = $slider->getData('width');
        $bannerHeight = $slider->getData('height');
        $sliderType = $slider->getData('layout');

        if ($sliderType == 'responsitive') {
            $sliderType = 'custom';
        }

        if ($sliderType == 'fullwidth') {
            $sliderType = 'autoresponsive';
        }

        $class = 'lp-' . $sliderType;
        if ($sliderType == 'fullwidth' && $slider->getData('force_full_width') == 'on') {
            $class .= ' lp-autoresponsive';
        }

        if ($sliderType == 'fullscreen') {
            if ($slider->getData('force_full_width') == 'on') {
                $class .= ' lp-fullwidth';
            }

            if ($slider->getData('full_screen_align_force') == 'on') {
                $class .= ' lp-fullscreenalign';
            }
        }

        $html = '<style type="text/css">
			hr{margin-top:0}
			#layout-preshow-page 	{width:550px; height:200px;border:1px solid #ddd; background:#fff;position:relative;margin:auto;}
			#layout-preshow-theme	{width:360px; height:200px; background:#eee;position:absolute;top:0px; left:95px}
			#layout-preshow-slider	{width:360px; height:130px; background:#95a5a6; position:absolute;left:95px;top:35px;
				-webkit-transition: all 0.2s ease-out;
				-moz-transition: all 0.2s ease-out;
				-o-transition: all 0.2s ease-out;
				-ms-transition: all 0.2s ease-out; }
			#layout-preshow-grid	{width:318px; height:88px; border:2px dotted #fff; position: absolute;left:114px;top:55px;
				-webkit-transition: all 0.2s ease-out;
				-moz-transition: all 0.2s ease-out;
				-o-transition: all 0.2s ease-out;
				-ms-transition: all 0.2s ease-out; }
			.layout-preshow-text						{	position: absolute; top:0px;left:0px;font-size:10px; color:#fff;padding:4px 6px; background:#fb942e;font-weight: bold;}
			#layout-preshow-grid .layout-preshow-text	{	top:auto;bottom:-2px;left:auto;right:-2px;}

			/* FULLWIDTH */
			.lp-fullwidth #layout-preshow-slider		{	width:550px; left:0px; top:35px;}
			.lp-fullwidth #layout-preshow-grid			{	width:318px; left:114px;top:55px}
			.lp-autoheight #layout-preshow-slider		{	height:165px;}

			/* FULLSCREEN */
			.lp-fullscreen #layout-preshow-slider		{	width:360px; height:200px;left:95px; top:0px;}
			.lp-fullscreen #layout-preshow-grid			{	width:338px; height:80px; left:104px;top:55px}
			.lp-fullscreenalign #layout-preshow-grid	{	width:360px; height:200px;left:95px; top:0px;}

			/* FULLSCREEN FULLWIDTH */
			.lp-fullscreen.lp-fullwidth #layout-preshow-slider	{	width:550px; height:200px;left:0px; top:0px;}
			.lp-fullscreen.lp-fullwidth #layout-preshow-grid	{	width:338px; height:80px; left:104px;top:55px}
			.lp-fullscreen.lp-fullwidth.lp-fullscreenalign #layout-preshow-grid	{	width:550px; height:200px;left:0px; top:0px;}

			/* CUSTOM */
			.lp-custom #layout-preshow-slider		{	width:250px; height:150px; left:110px; top:35px;}
			.lp-custom #layout-preshow-grid			{	width:242px; height:142px; left:145px; left:112px; top:37px;}

			/* FIXED */
			.lp-fixed #layout-preshow-slider		{	background:url(' . Mage::getDesign()->getSkinUrl('images/fixed.png') . ')}
			.lp-fixed #layout-preshow-slider		{	width:270px; height:120px; left:110px; top:35px;}
			.lp-fixed #layout-preshow-grid			{	width:265px; height:115px; left:145px; left:112px; top:37px;}
		</style>';

        $html .= '<hr/><div class="col-sm-9"><div id="layout-preshow" class="' . $class . '">
            <div id="layout-preshow-page">
                <div class="layout-preshow-text">BROWSER</div>
                <div id="layout-preshow-theme">
                    <div class="layout-preshow-text">PAGE</div>
                </div>
                <div id="layout-preshow-slider">
                    <div class="layout-preshow-text">SLIDER</div>
                </div>
                <div id="layout-preshow-grid">
                    <div class="layout-preshow-text">CAPTIONS GRID</div>
                </div>
            </div>
        </div></div>';
        $html .= '<div class="col-sm-3"><div class="form-group">';
        $html .= '<label class="col-sm-6 control-label">' . $this->__('Width') . '</label>';
        $html .= '<div class="col-sm-6"><input type="text" disabled="disabled" value="' . $bannerWidth . '" class="input-text"/></div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '<label class="col-sm-6 control-label">' . $this->__('Height') . '</label>';
        $html .= '<div class="col-sm-6"><input type="text" disabled="disabled" value="' . $bannerHeight . '" class="input-text"/></div>';
        $html .= '</div></div>';
        echo $html;
    }

    public function translateAction() {
        $value = $this->getRequest()->getParam('value');
        $referer = $this->getRequest()->getParam('referrer');

        $storeId = Mage::app()->getStore()->getId();
        $config = new Mage_Core_Model_Config();
        $config->saveConfig('dev/translate_inline/active', $value, 'stores', $storeId);

        $url = Mage::helper('core')->urlDecode($referer);
        $this->_redirectUrl($url);
        return;
    }

    public function restoreAction() {
        $filePath = Mage::getBaseDir() . '/app/code/local/MGS/Mpanel/data/themes/' . Mage::getDesign()->getTheme('frontend') . '/default_config.xml';
        if (is_readable($filePath)) {
            $config = new Varien_Simplexml_Config($filePath);

            try {
                foreach ($config->getNode('section') as $child) {
                    $arrConfig = json_decode(json_encode($child), true);
                    foreach ($arrConfig as $section => $data) {
                        $this->saveSettings($section, $data);
                    }
                }
            } catch (Exception $e) {
                //Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        } else {
            Mage::getSingleton('core/session')->addError(Mage::helper('mpanel')->__('Can not restore default theme configuration.'));
        }
        $this->_redirectReferer();
        return;
    }

    // Save settings
    public function saveSettings($section, $data) {
        $config = new Mage_Core_Model_Config();
        foreach ($data as $group => $_group) {
            foreach ($_group as $field => $value) {
                $config->saveConfig($section . '/' . $group . '/' . $field, $value);
            }
        }
    }

    public function resetlayoutAction() {
        $id = Mage::app()->getRequest()->getParam('id');
        $collection = Mage::getModel('mpanel/layout')
                ->getCollection()
                ->addFieldToFilter('page_type', 'category')
                ->addFieldToFilter('indentifier', $id);
        if (count($collection) > 0) {
            foreach ($collection as $_item) {
                $_item->delete();
            }
        }

        $this->_redirectReferer();
    }

	public function duplicateAction(){
		$currentStoreId = Mage::app()->getStore()->getId();
		$storeId = $this->getRequest()->getParam('store_id');
		
		//Update store table
		$store = Mage::getModel('mpanel/store')->getCollection()->addFieldToFilter('store_id', $currentStoreId)->getFirstItem();
		if($store->getId()){
			Mage::getModel('mpanel/store')->setStatus(1)->setId($store->getId())->save();
		}else{
			Mage::getModel('mpanel/store')->setStoreId($currentStoreId)->setName('1_column_full')->setStatus(1)->save();
		}
		
		//Update blocks table
		$blocks = Mage::getModel('mpanel/blocks')->getCollection()->addFieldToFilter('store_id', $currentStoreId);
		if(count($blocks)>0){
			foreach($blocks as $block){
				$block->delete();
			}
		}
		$blockToCopy = Mage::getModel('mpanel/blocks')->getCollection()->addFieldToFilter('store_id', $storeId);
		
		foreach($blockToCopy as $_block){
			$data = $_block->getData();
			unset($data['block_id']);
			$data['store_id'] = $currentStoreId;
			Mage::getModel('mpanel/blocks')->setData($data)->save();
		}
		
		//Update child table
		$childs = Mage::getModel('mpanel/childs')->getCollection()->addFieldToFilter('store_id', $currentStoreId);
		if(count($childs)>0){
			foreach($childs as $child){
				$child->delete();
			}
		}
		$childToCopy = Mage::getModel('mpanel/childs')->getCollection()->addFieldToFilter('store_id', $storeId);
		foreach($childToCopy as $_child){
			$data = $_child->getData();
			unset($data['child_id']);
			$data['store_id'] = $currentStoreId;
			
			if($data['type']=='static'){
				$staticBlockId = $data['static_block_id'];
				$static = Mage::getModel('cms/block')->load($staticBlockId);
				
				if(Mage::helper('mpanel/data')->getBlockIdByIndentifier($static->getIdentifier())){
					$data['static_block_id'] = Mage::helper('mpanel/data')->getBlockIdByIndentifier($static->getIdentifier());
				}
				else{
					$cmsBlock = array(
						'title' => $static->getTitle(),
						'identifier' => $static->getIdentifier(),
						'content' => $static->getContent(),
						'is_active' => 1,
						'stores' => array($currentStoreId)
					);
					$staticBlock = Mage::getModel('cms/block')->setData($cmsBlock)->save();
					$data['static_block_id'] = $staticBlock->getId();
				}
			}
			
			Mage::getModel('mpanel/childs')->setData($data)->save();
		}
		
		
		$this->_redirectReferer();
	}
	
	public function getproductAction() {
        $data = $this->getRequest()->getPost();
		$sku = $data['setting']['sku'];
        
        $products = Mage::getModel('catalog/product')->getCollection()
			->addStoreFilter(Mage::app()->getStore()->getId())
			->addAttributeToFilter('status',1)
			->addAttributeToFilter('visibility',array('neq'=>1))
			->addFieldToFilter('sku', array('like' => '%' . $sku . '%'));
        $li = '';
        if (count($products) > 0) {
            foreach ($products as $product) {
                $li .= "<li>" . $product->getSku() . "</li>" . "\n";
            }
            print "<ul>$li</ul>";
        }
    }
}
