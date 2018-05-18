<?php

class MGS_Mpanel_Helper_Data extends MGS_Mgscore_Helper_Data {

    protected $_ids;

    // Check to accept to use builder panel
    public function acceptToUsePanel() {
        if ($this->showButton() && (Mage::getSingleton('core/session')->getUsePanel() == 1)) {
            return true;
        }
        return false;
    }

    public function showButton() {

        if (Mage::getStoreConfig('mpanel/general/enabled')) {

            $logedAccountId = Mage::getSingleton('customer/session')->getCustomer()->getEmail();

            $acceptAccounts = Mage::getStoreConfig('mpanel/general/account');
            if ($acceptAccounts == '') {
                return false;
            }
            $acceptAccountIds = explode(',', $acceptAccounts);
            $acceptAccountIds = array_filter($acceptAccountIds);

            if ((count($acceptAccountIds) > 0) && (in_array($logedAccountId, $acceptAccountIds))) {
                return true;
            }
        }

        return false;
    }

    // Check cms page, if is cms page return true
    public function isCmsPage() {
        $module = Mage::app()->getRequest()->getModuleName();
        if ($module == 'cms') {
            return true;
        }
        return false;
    }

    // Check customer page, if is customer page return true
    public function isCustomerPage() {
        $module = Mage::app()->getRequest()->getModuleName();
        $controller = Mage::app()->getRequest()->getControllerName();
        $action = Mage::app()->getRequest()->getActionName();
        $str = $module . '-' . $controller . '-' . $action;
        if ($str == 'customer-account-index' || $str == 'customer-account-edit' || $str == 'customer-address-index' || $str == 'sales-order-history' || $str == 'sales-billing_agreement-index' || $str == 'sales-recurring_profile-index' || $str == 'review-customer-index' || $str == 'tag-customer-index' || $str == 'wishlist-index-index' || $str == 'oauth-customer_token-index' || $str == 'newsletter-manage-index' || $str == 'downloadable-customer-products' || $str == 'productquestions-index-index') {
            return true;
        }
        return false;
    }

    // Check homepage, if is homepage return true
    public function isHomepage() {
        $module = Mage::app()->getRequest()->getModuleName();
        $controller = Mage::app()->getRequest()->getControllerName();
        $action = Mage::app()->getRequest()->getActionName();

        if ((Mage::getSingleton('cms/page')->getIdentifier() == 'home' && Mage::app()->getFrontController()->getRequest()->getRouteName() == 'cms') || Mage::getUrl() == Mage::getUrl('*/*/*', array('_current' => true, '_use_rewrite' => true)) || ($module == 'cms' && $controller == 'index' && $action == 'index')) {
            return true;
        }
        return false;
    }

    // check category and product detail page
    public function isCatalogPage() {
        $module = Mage::app()->getRequest()->getModuleName();
        if ($module == 'catalog') {
            return true;
        }
        return false;
    }

    // check category page
    public function isCategoryPage() {
        $controller = Mage::app()->getRequest()->getControllerName();
        if ($controller == 'category') {
            return true;
        }
        return false;
    }

    // check catalog search page
    public function isCatalogSearchPage() {
        $controller = Mage::app()->getRequest()->getModuleName();
        if ($controller == 'catalogsearch') {
            return true;
        }
        return false;
    }

    // check product page
    public function isProductPage() {
        $controller = Mage::app()->getRequest()->getControllerName();
        if ($controller == 'product') {
            return true;
        }
        return false;
    }

    // Get all page layout of site (1 column, 2 columns left...), return dropdown html
    public function getPageLayoutHtml($pageId) {
        $page = Mage::getModel('cms/page')->load($pageId);

        $storeIds = $page->getStoreId();
        $html = '';

        if (count($storeIds) > 0) {
            foreach ($storeIds as $storeId) {
                $html .= '<input type="hidden" name="stores[]" value="' . $storeId . '"/>';
            }
        }

        $html .= '<select name="root_template" class="page-layout" onchange="this.form.submit();">';

        foreach (Mage::getSingleton('page/config')->getPageLayouts() as $layout) {
            $label = $layout->getLabel();
            $value = $layout->getCode();
            $html .= '<option value="' . $value . '"';
            if ($page->getRootTemplate() == $value) {
                $html .= ' selected="selected"';
            }
            $html .= '>' . $label . '</option>';
        }

        $html .= '</select>';

        return $html;
    }

    // Get all page layout (1 column, 2 columns left...) for catalog page
    public function getCatalogLayoutUpdate() {
        if (Mage::registry('current_product')) {
            $product = Mage::registry('current_product');
            $currentLayout = $product->getPageLayout();
        } else {
            $category = Mage::registry('current_category');
            $currentLayout = $category->getPageLayout();
        }

        $layout = Mage::getSingleton('page/source_layout')->toOptionArray();
        array_unshift($layout, array('value' => '', 'label' => Mage::helper('catalog')->__('No layout updates')));

        $html = '<select name="general[page_layout]" class="page-layout" onchange="this.form.submit();">';
        foreach ($layout as $_layout) {
            $html .= '<option value="' . $_layout['value'] . '"';
            if ($currentLayout == $_layout['value']) {
                $html .= ' selected="selected"';
            }
            $html .= '>' . $_layout['label'] . '</option>';
        }
        $html .= '</select>';

        return $html;
    }

    public function getPageSettings() {
        if (Mage::registry('current_product')) {
            return $this->getLayout()->createBlock('core/template')->setTemplate('mgs/mpanel/panel/product-settings.phtml')->toHtml();
        } else {
            $category = Mage::registry('current_category');
            return $this->getLayout()->createBlock('core/template')->setCategory($category)->setTemplate('mgs/mpanel/panel/category-settings.phtml')->toHtml();
        }
    }

    // Check homepage has use builder panel or not
    public function useHomepageBuilder() {
        $storeId = Mage::app()->getStore()->getId();
        $homeStore = Mage::getModel('mpanel/store')
                ->getCollection()
                ->addFieldToFilter('store_id', $storeId)
                ->addFieldToFilter('status', 1);
        if (count($homeStore) > 0) {
            return true;
        }
        return false;
    }

    // Return html of dropdown homepage config (Use CMS Page, Use Homepage Builder)
    public function getHomepageConfigHtml() {
        $html = '<input type="checkbox" data-toggle="toggle" data-height="20" data-width="110" data-on="Use Builder" data-off="Use CMS" data-onstyle="success" data-offstyle="warning" name="status" value="1" id="homesetting" onchange="checkBuilder()"';
        if ($this->useHomepageBuilder()) {
            $html .= ' checked="checked"';
        }
        $html .= '/> ';

        return $html;
    }

    // Get all homepage layout from database
    public function getHomeLayouts() {
        $layouts = Mage::getModel('mpanel/home')
                ->getCollection();
        return $layouts;
    }

    // Check a layout have active or not
    public function isActiveLayout($layoutName) {
        $storeId = Mage::app()->getStore()->getId();
        $homeStore = Mage::getModel('mpanel/store')
                ->getCollection()
                ->addFieldToFilter('store_id', $storeId)
                ->addFieldToFilter('name', $layoutName);
        if ($homeStore->getFirstItem()->getStatus()) {
            return true;
        }
        return false;
    }

    // Get WYSIWYG Editor config
    public function getConfig($data = array()) {
        $config = new Varien_Object();

        $config->setData(array(
            'enabled' => true,
            'hidden' => 1,
            'use_container' => false,
            'add_variables' => false,
            'add_widgets' => true,
            'no_display' => false,
            'translator' => Mage::helper('cms'),
            'encode_directives' => true,
            'directives_url' => str_replace('https', 'http', Mage::getUrl('mpanel/wysiwyg/directive')),
            'widget_window_url' => str_replace('https', 'http', Mage::getUrl('mpanel/adminhtml_widget/index')),
            'popup_css' =>
            Mage::getBaseUrl('js') . 'mage/adminhtml/wysiwyg/tiny_mce/themes/advanced/skins/default/dialog.css',
            'content_css' =>
            Mage::getBaseUrl('js') . 'mage/adminhtml/wysiwyg/tiny_mce/themes/advanced/skins/default/content.css',
            'width' => '100%',
            'plugins' => array(
            /* array(
              'name'=>'magentovariable',
              'src'=>  Mage::getBaseUrl('js').'mage/adminhtml/wysiwyg/tiny_mce/plugins/magentovariable/editor_plugin.js',
              'options'=> array(
              'title'=>'Insert Variable...',
              'url'=> Mage::getUrl('mpanel/variable/wysiwygPlugin'),
              'onclick'=> array(
              'search'=> array(
              'html_id'
              ),
              'subject'=> "MagentovariablePlugin.loadChooser('".Mage::getUrl('mpanel/variable/wysiwygPlugin')."', '{{html_id}}');"
              ),
              'class'=> 'add-variable plugin'
              )
              ),

              array(
              'name'=>'magentowidget',
              'src'=> Mage::getBaseUrl('js').'mage/adminhtml/wysiwyg/tiny_mce/plugins/magentowidget/editor_plugin.js',

              ) */
            ),
            'directives_url_quoted' => str_replace('https', 'http', Mage::getUrl('mpanel/wysiwyg/directive'))
        ));

        //$config->setData('directives_url_quoted', preg_quote($config->getData('directives_url')));

        $config->addData(array(
            'add_images' => true,
            'files_browser_window_url' => str_replace('https', 'http', Mage::getUrl('mpanel/wysiwyg/index')),
            'files_browser_window_width' => (int) Mage::getConfig()->getNode('adminhtml/cms/browser/window_width'),
            'files_browser_window_height' => (int) Mage::getConfig()->getNode('adminhtml/cms/browser/window_height'),
            'widget_plugin_src' => Mage::getBaseUrl('js') . 'mage/adminhtml/wysiwyg/tiny_mce/plugins/magentowidget/editor_plugin.js',
            'widget_images_url' => Mage::getDesign()->getSkinUrl('images/widget', array('_area' => 'adminhtml')),
        ));


        if (is_array($data)) {
            $config->addData($data);
        }

        Mage::dispatchEvent('cms_wysiwyg_config_prepare', array('config' => $config));

        return $config;
    }

    // Get edit panel of a block
    public function getEditPanel($id) {
        $html = '<div class="edit-panel parent-panel"><ul>';
        $html .='<li class="up-link"><a title="' . $this->__('Move Up') . '" onclick="return false;" href="#" class="moveuplink"><em class="fa fa-arrow-up">&nbsp;</em></a></li>';
        $html .='<li class="down-link"><a title="' . $this->__('Move Down') . '" onclick="return false;" href="#" class="movedownlink"><em class="fa fa-arrow-down">&nbsp;</em></a></li>';
        $html .='<li><a href="' . Mage::getUrl('mpanel/edit/block', array('layout' => '1_column_full', 'id' => $id)) . '" class="popup-link" title="' . $this->__('Edit') . '"><em class="fa fa-gear"></em></a></li>';
        $html .='<li><a href="#" title="' . $this->__('Delete') . '" onclick="if(confirm(\'' . $this->__('Are you sure you would like to remove this section?') . '\')) removeSection(' . $id . '); return false"><em class="fa fa-close"></em></a></li>';
        $html .='</ul></div>';

        return $html;
    }

    // Get edit panel of a header
    public function getEditHeaderPanel() {
        $isSecure = Mage::app()->getStore()->isCurrentlySecure();
        if ($isSecure) {
            $html = '<div class="edit-panel"><ul><li><a class="popup-link" href="' . Mage::getUrl('mpanel/edit/header', array('_secure' => true)) . '" title="' . $this->__('Edit Header') . '"><em class="fa fa-gear"></em></a></li></ul></div>';
        } else {
            $html = '<div class="edit-panel"><ul><li><a class="popup-link" href="' . Mage::getUrl('mpanel/edit/header') . '" title="' . $this->__('Edit Header') . '"><em class="fa fa-gear"></em></a></li></ul></div>';
        }
        return $html;
    }

    // Get edit panel of a footer
    public function getEditFooterPanel() {
        $isSecure = Mage::app()->getStore()->isCurrentlySecure();
        if ($isSecure) {
            $html = '<div class="edit-panel"><ul><li><a class="popup-link" href="' . Mage::getUrl('mpanel/edit/footer', array('_secure' => true)) . '" title="' . $this->__('Edit Footer') . '"><em class="fa fa-gear"></em></a></li></ul></div>';
        } else {
            $html = '<div class="edit-panel"><ul><li><a class="popup-link" href="' . Mage::getUrl('mpanel/edit/footer') . '" title="' . $this->__('Edit Footer') . '"><em class="fa fa-gear"></em></a></li></ul></div>';
        }
        return $html;
    }

    // Add edit panel for logo
    public function getEditLogoPanel() {
        $isSecure = Mage::app()->getStore()->isCurrentlySecure();

        $html = '<div class="edit-panel logo-panel child-panel"><ul>';

        if ($isSecure) {
            $html .= '<li><a href="' . Mage::getUrl('mpanel/post/logo', array('_secure' => true)) . '" class="popup-link" title="' . $this->__('Upload Logo') . '"><em class="fa fa-edit">&nbsp;</em></a></li>';
        } else {
            $html .= '<li><a href="' . Mage::getUrl('mpanel/post/logo') . '" class="popup-link" title="' . $this->__('Upload Logo') . '"><em class="fa fa-edit">&nbsp;</em></a></li>';
        }
        $html .= '</ul></div>';

        return $html;
    }
	
	// Show WishList Button
    public function isShowWishList() {
		if(!Mage::getStoreConfig('mpanel/catalog/wishlist_button')){
			return true;
		}
        return false;
    }
	
	// Check if product is in wishlist
	public function checkInWishilist($_product){
		Mage::getSingleton('customer/session')->isLoggedIn();
		$session = Mage::getSingleton('customer/session');
		$cidData = $session->isLoggedIn();
		$customer_id = $session->getId();

		if($customer_id){
			$wishlist = Mage::getModel('wishlist/item')->getCollection();
			$wishlist->getSelect()
					  ->join(array('t2' => 'wishlist'),
							 'main_table.wishlist_id = t2.wishlist_id',
							 array('wishlist_id','customer_id'))
							 ->where('main_table.product_id = '.$_product->getId().' AND t2.customer_id='.$customer_id);
			$count = $wishlist->count();
			$wishlist = Mage::getModel('wishlist/item')->getCollection();
		}
		else {
			$count="0";
		}

		if ($count) :
			return true;
		else:
			return false;
		endif;
	}
	
	// Show Compare Button
    public function isShowCompare() {
		if(!Mage::getStoreConfig('mpanel/catalog/compare_button')){
			return true;
		}
        return false;
    }
	
	// Show Add to Cart Button
    public function isShowAddtoCart() {
		if(!Mage::getStoreConfig('mpanel/catalog/hide_add_to_cart_button')){
			return true;
		}
        return false;
    }

    // Add edit panel for category image
    public function getEditCategoryImage($id) {
        $html = '<div class="edit-panel inline-panel"><ul>';
        $html .= '<li><a href="' . Mage::getUrl('mpanel/post/categoryImage', array('id' => $id)) . '" class="popup-link" title="' . $this->__('Upload Image For This Category') . '"><em class="fa fa-edit">&nbsp;</em></a></li>';
        $html .= '</ul></div>';

        return $html;
    }

    // Add edit panel for product tab
    public function getDeleteProductTab($alias) {
        $html = '<div class="edit-panel child-panel"><ul>';
        $html .= '<li><a href="' . Mage::getUrl('mpanel/post/deleteProductTab', array('alias' => $alias)) . '" onclick="return confirm(\'' . $this->__('Are you sure you would like to remove this tab?') . '\')" title="' . $this->__('Delete') . '"><em class="fa fa-trash">&nbsp;</em></a></li>';
        $html .= '</ul></div>';

        return $html;
    }

    // Add edit panel for category description
    public function getEditCategoryDescription($id) {
        $html = '<div class="edit-panel inline-panel"><ul>';
        $html .= '<li><a href="' . Mage::getUrl('mpanel/post/categoryDescription', array('id' => $id)) . '" class="popup-link" title="' . $this->__('Edit Description For This Category') . '"><em class="fa fa-edit">&nbsp;</em></a></li>';
        $html .= '</ul></div>';

        return $html;
    }

    // Add edit panel for welcome text and copyright
    public function getEditStoreConfig($tag, $text) {
        $html = '<div class="edit-panel inline-panel ' . $tag . '-config"><ul>';
        $html .= '<li><a href="#" onclick="toggleEl(\'' . $tag . '\'); return false" class="edit-inline" title="' . $this->__('Edit') . '"><em class="fa fa-edit">&nbsp;</em></a><div class="input-inline" style="display:none" id="' . $tag . '">';

        if ($tag == 'design-footer-copyright') {
            $html .= '<textarea type="text" id="' . $tag . '-input" class="input-text edit-input">' . $text . '</textarea>';
        } else {
            $html .= '<input type="text" value="' . $text . '" id="' . $tag . '-input" class="input-text edit-input"/>';
        }

        $html .= '<button type="button" onclick="saveStoreConfig(\'' . $tag . '\',\'' . $this->__('Save') . '\',\'' . $this->__('Saving...') . '\')" class="btn btn-primary btn-save-config">' . $this->__('Save') . '</button></div></li>';
        $html .= '</ul></div>';
        return $html;
    }

    // Add edit panel for gmap
    public function getEditMapPanel() {
        $html = '<div class="edit-panel map-panel"><ul>';
        $html .= '<li><a href="' . Mage::getUrl('mpanel/post/map') . '" class="popup-link" title="' . $this->__('Edit Map Information') . '"><em class="fa fa-edit">&nbsp;</em></a></li>';
        $html .= '</ul></div>';

        return $html;
    }

    // Add edit panel for contact information
    public function getEditContactInfoPanel() {
        $html = '<div class="edit-panel contact-info-panel"><ul>';
        $html .= '<li><a href="' . Mage::getUrl('mpanel/post/info') . '" class="popup-link" title="' . $this->__('Edit Contact Information') . '"><em class="fa fa-edit">&nbsp;</em></a></li>';
        $html .= '</ul></div>';

        return $html;
    }

    // Get block content by layout and block_id
    public function getBlockContent($layout, $id) {
        
    }

    //Return content of a homepage if homepage use builder panel
    public function getLayoutConfig() {
        $storeId = Mage::app()->getStore()->getId();

        $config = Mage::getModel('mpanel/store')
                ->getCollection()
                ->addFieldToFilter('store_id', $storeId)
                ->addFieldToFilter('status', 1);

        if ($this->acceptToUsePanel()) {
            return $this->getLayout()->createBlock('mpanel/template')->setTemplate('mgs/mpanel/template/admin/' . $config->getFirstItem()->getName() . '.phtml')->toHtml();
        } else {
            return $this->getLayout()->createBlock('mpanel/template')->setTemplate('mgs/mpanel/template/' . $config->getFirstItem()->getName() . '.phtml')->toHtml();
        }
    }

    //Return content of a category right if use builder panel
    public function getLayoutConfigCategoryRight() {
        if ($this->acceptToUsePanel()) {
            return $this->getLayout()->createBlock('mpanel/template')->setTemplateLayout('category_right')->setBlockName('block_category_right')->setTemplate('mgs/mpanel/template/admin/category_right.phtml')->toHtml();
        } else {
            return $this->getLayout()->createBlock('mpanel/template')->setTemplateLayout('category_right')->setBlockName('block_category_right')->setTemplate('mgs/mpanel/template/category_right.phtml')->toHtml();
        }
    }

    //Return content of a category left if use builder panel
    public function getLayoutConfigCategoryLeft() {
        if ($this->acceptToUsePanel()) {
            return $this->getLayout()->createBlock('mpanel/template')->setTemplateLayout('category_left')->setBlockName('block_category_left')->setTemplate('mgs/mpanel/template/admin/category_left.phtml')->toHtml();
        } else {
            return $this->getLayout()->createBlock('mpanel/template')->setTemplateLayout('category_left')->setBlockName('block_category_left')->setTemplate('mgs/mpanel/template/category_left.phtml')->toHtml();
        }
    }

    //Return content of a catalog search right if use builder panel
    public function getLayoutConfigCatalogSearchRight() {
        if ($this->acceptToUsePanel()) {
            return $this->getLayout()->createBlock('mpanel/template')->setTemplateLayout('catalog_search_right')->setBlockName('block_catalog_search_right')->setTemplate('mgs/mpanel/template/admin/catalog_search_right.phtml')->toHtml();
        } else {
            return $this->getLayout()->createBlock('mpanel/template')->setTemplateLayout('catalog_search_right')->setBlockName('block_catalog_search_right')->setTemplate('mgs/mpanel/template/catalog_search_right.phtml')->toHtml();
        }
    }

    //Return content of a catalog search left if use builder panel
    public function getLayoutConfigCatalogSearchLeft() {
        if ($this->acceptToUsePanel()) {
            return $this->getLayout()->createBlock('mpanel/template')->setTemplateLayout('catalog_search_left')->setBlockName('block_catalog_search_left')->setTemplate('mgs/mpanel/template/admin/catalog_search_left.phtml')->toHtml();
        } else {
            return $this->getLayout()->createBlock('mpanel/template')->setTemplateLayout('catalog_search_left')->setBlockName('block_catalog_search_left')->setTemplate('mgs/mpanel/template/catalog_search_left.phtml')->toHtml();
        }
    }

    //Return content of a cms right if use builder panel
    public function getLayoutConfigCmsRight() {
        if ($this->acceptToUsePanel()) {
            return $this->getLayout()->createBlock('mpanel/template')->setTemplateLayout('cms_right')->setBlockName('block_cms_right')->setTemplate('mgs/mpanel/template/admin/cms_right.phtml')->toHtml();
        } else {
            return $this->getLayout()->createBlock('mpanel/template')->setTemplateLayout('cms_right')->setBlockName('block_cms_right')->setTemplate('mgs/mpanel/template/cms_right.phtml')->toHtml();
        }
    }

    //Return content of a cms left if use builder panel
    public function getLayoutConfigCmsLeft() {
        if ($this->acceptToUsePanel()) {
            return $this->getLayout()->createBlock('mpanel/template')->setTemplateLayout('cms_left')->setBlockName('block_cms_left')->setTemplate('mgs/mpanel/template/admin/cms_left.phtml')->toHtml();
        } else {
            return $this->getLayout()->createBlock('mpanel/template')->setTemplateLayout('cms_left')->setBlockName('block_cms_left')->setTemplate('mgs/mpanel/template/cms_left.phtml')->toHtml();
        }
    }

    //Return content of a customer right if use builder panel
    public function getLayoutConfigCustomerRight() {
        if ($this->acceptToUsePanel()) {
            return $this->getLayout()->createBlock('mpanel/template')->setTemplateLayout('customer_right')->setBlockName('block_customer_right')->setTemplate('mgs/mpanel/template/admin/customer_right.phtml')->toHtml();
        } else {
            return $this->getLayout()->createBlock('mpanel/template')->setTemplateLayout('customer_right')->setBlockName('block_customer_right')->setTemplate('mgs/mpanel/template/customer_right.phtml')->toHtml();
        }
    }

    //Return content of a customer left if use builder panel
    public function getLayoutConfigCustomerLeft() {
        if ($this->acceptToUsePanel()) {
            return $this->getLayout()->createBlock('mpanel/template')->setTemplateLayout('customer_left')->setBlockName('block_customer_left')->setTemplate('mgs/mpanel/template/admin/customer_left.phtml')->toHtml();
        } else {
            return $this->getLayout()->createBlock('mpanel/template')->setTemplateLayout('customer_left')->setBlockName('block_customer_left')->setTemplate('mgs/mpanel/template/customer_left.phtml')->toHtml();
        }
    }

    // Return new position for a child block
    public function getNewPositionOfChild($storeId, $blockName, $templateLayout) {
        $child = Mage::getModel('mpanel/childs')
                ->getCollection()
                ->addFieldToFilter('store_id', $storeId)
                ->addFieldToFilter('block_name', $blockName)
                ->addFieldToFilter('home_name', $templateLayout)
                ->setOrder('position', 'DESC')
                ->getFirstItem();

        if ($child->getId()) {
            $position = (int) $child->getPosition() + 1;
        } else {
            $position = 1;
        }

        return $position;
    }

    public function getEditChild($layout, $block, $child, $type) {
        $html = '<div class="edit-panel child-panel"><ul>';

        $html .= '<li class="sort-handle"><a href="#" onclick="return false;" title="' . $this->__('Move') . '"><em class="fa fa-arrows">&nbsp;</em></a></li>';

        $html .= '<li><a href="' . Mage::getUrl('mpanel/index/form', array('template' => $layout, 'block' => $block, 'id' => $child, 'type' => $type)) . '" class="popup-link" title="' . $this->__('Edit') . '"><em class="fa fa-edit">&nbsp;</em></a></li>';

        $html .= '<li class="change-col"><a href="javascript:void(0)" title="' . $this->__('Change column setting') . '"><em class="fa fa-columns">&nbsp;</em></a><ul>';

        for ($i = 1; $i <= 12; $i++) {
            $html .= '<li><a href="' . Mage::getUrl('mpanel/edit/col', array('id' => $child, 'col' => $i)) . '" onclick="changeBlockCol(this.href); return false"><span>' . $i . '/12</span></a></li>';
        }

        $html .= '</ul></li>';

        $html .= '<li><a href="' . Mage::getUrl('mpanel/post/delete', array('id' => $child)) . '" onclick="return confirm(\'' . $this->__('Are you sure you would like to remove this block?') . '\')" title="' . $this->__('Delete') . '"><em class="fa fa-trash">&nbsp;</em></a></li>';
        $html .= '</ul></div>';

        return $html;
    }

    public function getEditChildInCategory($layout, $block, $child, $type, $category_id, $product_id) {
        if ($type == 'core') {
            if ($product_id) {
                $html = '<div class="edit-panel child-panel"><ul>';
                $html .= '<li class="sort-handle"><a href="#" onclick="return false;" title="' . $this->__('Move') . '"><em class="fa fa-arrows">&nbsp;</em></a></li>';
                $html .= '<li><a href="' . Mage::getUrl('mpanel/post/deleteInCategory', array('template' => $layout, 'type' => $type, 'block' => $child, 'category_id' => $category_id, 'product_id' => $product_id)) . '" onclick="return confirm(\'' . $this->__('Are you sure you would like to remove this block?') . '\')" title="' . $this->__('Delete') . '"><em class="fa fa-trash">&nbsp;</em></a></li>';
                $html .= '</ul></div>';
            } else {
                $html = '<div class="edit-panel child-panel"><ul>';
                $html .= '<li class="sort-handle"><a href="#" onclick="return false;" title="' . $this->__('Move') . '"><em class="fa fa-arrows">&nbsp;</em></a></li>';
                $html .= '<li><a href="' . Mage::getUrl('mpanel/post/deleteInCategory', array('template' => $layout, 'type' => $type, 'block' => $child, 'category_id' => $category_id)) . '" onclick="return confirm(\'' . $this->__('Are you sure you would like to remove this block?') . '\')" title="' . $this->__('Delete') . '"><em class="fa fa-trash">&nbsp;</em></a></li>';
                $html .= '</ul></div>';
            }
        } else {
            if ($product_id) {
                $html = '<div class="edit-panel child-panel"><ul>';
                $html .= '<li><a href="' . Mage::getUrl('mpanel/index/formInCategory', array('template' => $layout, 'block' => $block, 'id' => $child, 'type' => $type, 'category_id' => $category_id, 'product_id' => $product_id)) . '" class="popup-link" title="' . $this->__('Edit') . '"><em class="fa fa-edit">&nbsp;</em></a></li>';
                $html .= '<li class="sort-handle"><a href="#" onclick="return false;" title="' . $this->__('Move') . '"><em class="fa fa-arrows">&nbsp;</em></a></li>';
                $html .= '<li><a href="' . Mage::getUrl('mpanel/post/deleteInCategory', array('id' => $child, 'category_id' => $category_id, 'product_id' => $product_id)) . '" onclick="return confirm(\'' . $this->__('Are you sure you would like to remove this block?') . '\')" title="' . $this->__('Delete') . '"><em class="fa fa-trash">&nbsp;</em></a></li>';
                $html .= '</ul></div>';
            } else {
                $html = '<div class="edit-panel child-panel"><ul>';
                $html .= '<li><a href="' . Mage::getUrl('mpanel/index/formInCategory', array('template' => $layout, 'block' => $block, 'id' => $child, 'type' => $type, 'category_id' => $category_id)) . '" class="popup-link" title="' . $this->__('Edit') . '"><em class="fa fa-edit">&nbsp;</em></a></li>';
                $html .= '<li class="sort-handle"><a href="#" onclick="return false;" title="' . $this->__('Move') . '"><em class="fa fa-arrows">&nbsp;</em></a></li>';
                $html .= '<li><a href="' . Mage::getUrl('mpanel/post/deleteInCategory', array('id' => $child, 'category_id' => $category_id)) . '" onclick="return confirm(\'' . $this->__('Are you sure you would like to remove this block?') . '\')" title="' . $this->__('Delete') . '"><em class="fa fa-trash">&nbsp;</em></a></li>';
                $html .= '</ul></div>';
            }
        }

        return $html;
    }

    public function getEditChildInCms($layout, $block, $child, $type, $page_id) {
        if ($type == 'core') {
            $html = '<div class="edit-panel child-panel"><ul>';
            $html .= '<li class="sort-handle"><a href="#" onclick="return false;" title="' . $this->__('Move') . '"><em class="fa fa-arrows">&nbsp;</em></a></li>';
            $html .= '<li><a href="' . Mage::getUrl('mpanel/post/deleteInCms', array('template' => $layout, 'type' => $type, 'block' => $child, 'page_id' => $page_id)) . '" onclick="return confirm(\'' . $this->__('Are you sure you would like to remove this block?') . '\')" title="' . $this->__('Delete') . '"><em class="fa fa-trash">&nbsp;</em></a></li>';
            $html .= '</ul></div>';
        } else {
            $html = '<div class="edit-panel child-panel"><ul>';
            $html .= '<li><a href="' . Mage::getUrl('mpanel/index/formInCms', array('template' => $layout, 'block' => $block, 'id' => $child, 'type' => $type, 'page_id' => $page_id)) . '" class="popup-link" title="' . $this->__('Edit') . '"><em class="fa fa-edit">&nbsp;</em></a></li>';
            $html .= '<li class="sort-handle"><a href="#" onclick="return false;" title="' . $this->__('Move') . '"><em class="fa fa-arrows">&nbsp;</em></a></li>';
            $html .= '<li><a href="' . Mage::getUrl('mpanel/post/deleteInCms', array('id' => $child, 'page_id' => $page_id)) . '" onclick="return confirm(\'' . $this->__('Are you sure you would like to remove this block?') . '\')" title="' . $this->__('Delete') . '"><em class="fa fa-trash">&nbsp;</em></a></li>';
            $html .= '</ul></div>';
        }

        return $html;
    }

    public function getEditChildInCustomer($layout, $block, $child, $type) {
        if ($type == 'core') {
            $html = '<div class="edit-panel child-panel"><ul>';
            $html .= '<li class="sort-handle"><a href="#" onclick="return false;" title="' . $this->__('Move') . '"><em class="fa fa-arrows">&nbsp;</em></a></li>';
            $html .= '<li><a href="' . Mage::getUrl('mpanel/post/deleteInCustomer', array('template' => $layout, 'type' => $type, 'block' => $child)) . '" onclick="return confirm(\'' . $this->__('Are you sure you would like to remove this block?') . '\')" title="' . $this->__('Delete') . '"><em class="fa fa-trash">&nbsp;</em></a></li>';
            $html .= '</ul></div>';
        } else {
            $html = '<div class="edit-panel child-panel"><ul>';
            $html .= '<li><a href="' . Mage::getUrl('mpanel/index/formInCustomer', array('template' => $layout, 'block' => $block, 'id' => $child, 'type' => $type)) . '" class="popup-link" title="' . $this->__('Edit') . '"><em class="fa fa-edit">&nbsp;</em></a></li>';
            $html .= '<li class="sort-handle"><a href="#" onclick="return false;" title="' . $this->__('Move') . '"><em class="fa fa-arrows">&nbsp;</em></a></li>';
            $html .= '<li><a href="' . Mage::getUrl('mpanel/post/deleteInCustomer', array('id' => $child)) . '" onclick="return confirm(\'' . $this->__('Are you sure you would like to remove this block?') . '\')" title="' . $this->__('Delete') . '"><em class="fa fa-trash">&nbsp;</em></a></li>';
            $html .= '</ul></div>';
        }

        return $html;
    }

    public function getEditChildInCatalogSearch($layout, $block, $child, $type) {
        if ($type == 'core') {
            $html = '<div class="edit-panel child-panel"><ul>';
            $html .= '<li class="sort-handle"><a href="#" onclick="return false;" title="' . $this->__('Move') . '"><em class="fa fa-arrows">&nbsp;</em></a></li>';
            $html .= '<li><a href="' . Mage::getUrl('mpanel/post/deleteInCatalogSearch', array('template' => $layout, 'type' => $type, 'block' => $child)) . '" onclick="return confirm(\'' . $this->__('Are you sure you would like to remove this block?') . '\')" title="' . $this->__('Delete') . '"><em class="fa fa-trash">&nbsp;</em></a></li>';
            $html .= '</ul></div>';
        } else {
            $html = '<div class="edit-panel child-panel"><ul>';
            $html .= '<li><a href="' . Mage::getUrl('mpanel/index/formInCatalogSearch', array('template' => $layout, 'block' => $block, 'id' => $child, 'type' => $type)) . '" class="popup-link" title="' . $this->__('Edit') . '"><em class="fa fa-edit">&nbsp;</em></a></li>';
            $html .= '<li class="sort-handle"><a href="#" onclick="return false;" title="' . $this->__('Move') . '"><em class="fa fa-arrows">&nbsp;</em></a></li>';
            $html .= '<li><a href="' . Mage::getUrl('mpanel/post/deleteInCatalogSearch', array('id' => $child)) . '" onclick="return confirm(\'' . $this->__('Are you sure you would like to remove this block?') . '\')" title="' . $this->__('Delete') . '"><em class="fa fa-trash">&nbsp;</em></a></li>';
            $html .= '</ul></div>';
        }

        return $html;
    }

    public function renderHtmlContent($templateLayout, $blockName, $currentCategoryId, $key, $value, $isAdmin, $currentProductId) {
        $blocks = array(
            'categoryNavigation' => array(
                'block' => 'mpanel/navigation',
                'template' => 'mgs/mpanel/template/category-navigation.phtml'
            ),
            'subCategories' => array(
                'block' => 'catalog/navigation',
                'template' => 'catalog/navigation/left.phtml'
            ),
            'layeredNavigation' => array(
                'block' => 'catalog/layer_view',
                'template' => 'catalog/layer/view.phtml'
            ),
            'cartSidebar' => array(
                'block' => 'checkout/cart_sidebar',
                'template' => 'checkout/cart/sidebar.phtml'
            ),
            'compareSidebar' => array(
                'block' => 'catalog/product_compare_sidebar',
                'template' => 'catalog/product/compare/sidebar.phtml'
            ),
            'reorderSidebar' => array(
                'block' => 'sales/reorder_sidebar',
                'template' => 'sales/reorder/sidebar.phtml'
            ),
            'poll' => array(
                'block' => 'poll/activePoll',
                'poll_template' => array(
                    'poll' => 'poll/active.phtml',
                    'results' => 'poll/result.phtml'
                )
            ),
            'productViewed' => array(
                'block' => 'reports/product_viewed',
                'template' => 'reports/product_viewed.phtml'
            ),
            'wishlistSidebar' => array(
                'block' => 'wishlist/customer_sidebar',
                'template' => 'wishlist/sidebar.phtml'
            ),
            'tagsPopular' => array(
                'block' => 'tag/popular',
                'template' => 'tag/popular.phtml'
            ),
            'newsletter' => array(
                'block' => 'newsletter/subscribe',
                'template' => 'newsletter/subscribe.phtml'
            ),
            'productRelated' => array(
                'block' => 'catalog/product_list_related',
                'template' => 'catalog/product/list/related.phtml'
            ),
            'menu' => array(
                'block' => 'megamenu/vertical',
                'template' => 'megamenu/vertical.phtml'
            ),
            'featuredProducts' => array(
                'block' => 'mpanel/products',
                'template' => 'mgs/mpanel/products/list/featured_products.phtml'
            ),
            'bestsellerProducts' => array(
                'block' => 'mpanel/products',
                'template' => 'mgs/mpanel/products/list/hot_products.phtml'
            ),
            'newProducts' => array(
                'block' => 'mpanel/product_new',
                'template' => 'mgs/mpanel/products/list/new_products.phtml'
            ),
            'topRateProducts' => array(
                'block' => 'mpanel/product_rate',
                'template' => 'mgs/mpanel/products/list/rate_products.phtml'
            ),
            'saleProducts' => array(
                'block' => 'mpanel/product_sale',
                'template' => 'mgs/mpanel/products/list/sale_products.phtml'
            ),
            'facebookLikeBox' => array(
                'block' => 'social/fblikebox',
                'template' => 'mgs/social/facebook-like-box.phtml'
            ),
            'twitterFeed' => array(
                'block' => 'core/template',
                'template' => 'mgs/mpanel/twitter_tweets.phtml'
            )
        );
        foreach ($blocks as $block => $data) {
            if ($block == $key) {
                if ($key == 'subCategories') {
                    if ($this->isCategoryPage()) {
                        $html = '';
                        if ($isAdmin) {
                            $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                            $html .= $this->getEditChildInCategory($templateLayout, $blockName, $key, 'core', $currentCategoryId, $currentProductId);
                        }
                        $html .= $this->getLayout()
                                ->createBlock($data['block'])
                                ->setTemplate($data['template'])
                                ->toHtml();
                        if ($isAdmin) {
                            $html .= '</div>';
                        }
                        echo $html;
                    }
                } else if ($key == 'layeredNavigation') {
                    if ($this->isCategoryPage()) {
                        $html = '';
                        if ($isAdmin) {
                            $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                            $html .= $this->getEditChildInCategory($templateLayout, $blockName, $key, 'core', $currentCategoryId, $currentProductId);
                        }
                        $html .= $this->getLayout()->getBlock('catalog.leftnav')->toHtml();
                        if ($isAdmin) {
                            $html .= '</div>';
                        }
                        echo $html;
                    }
                } else if ($key == 'productRelated') {
                    if ($this->isProductPage()) {
                        $html = '';
                        if ($isAdmin) {
                            $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                            $html .= $this->getEditChildInCategory($templateLayout, $blockName, $key, 'core', $currentCategoryId, $currentProductId);
                        }
                        $html .= $this->getLayout()
                                ->createBlock($data['block'])
                                ->setTemplate($data['template'])
                                ->toHtml();
                        if ($isAdmin) {
                            $html .= '</div>';
                        }
                        echo $html;
                    }
                } else if ($key == 'poll') {
                    $arr = explode('-', $value);
                    if (isset($arr[1])) {
                        $html = '';
                        if ($isAdmin) {
                            $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                            $html .= $this->getEditChildInCategory($templateLayout, $blockName, $key, 'core', $currentCategoryId, $currentProductId);
                        }
                        $poll = $this->getLayout()->createBlock($data['block'])
                                ->setPollId($arr[1]);
                        foreach ($data['poll_template'] as $k => $v) {
                            $poll->setPollTemplate($v, $k);
                        }
                        $html .= $poll->toHtml();
                        if ($isAdmin) {
                            $html .= '</div>';
                        }
                        echo $html;
                    }
                } else if ($key == 'menu') {
                    $arr = explode('-', $value);
                    if (isset($arr[1])) {
                        $html = '';
                        if ($isAdmin) {
                            $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                            $html .= $this->getEditChildInCategory($templateLayout, $blockName, $key, 'core', $currentCategoryId, $currentProductId);
                        }
                        $html .= $this->getLayout()
                                ->createBlock($data['block'])
                                ->setMenuId($arr[1])
                                ->setTemplate($data['template'])
                                ->toHtml();
                        if ($isAdmin) {
                            $html .= '</div>';
                        }
                        echo $html;
                    }
                } else if ($key == 'categoryNavigation') {
                    $arr = explode('-', $value);
                    if (isset($arr[1])) {
                        $html = '';
                        if ($isAdmin) {
                            $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                            $html .= $this->getEditChildInCategory($templateLayout, $blockName, $key, 'core', $currentCategoryId, $currentProductId);
                        }
                        $html .= $this->getLayout()
                                ->createBlock($data['block'])
                                ->setSelectedCategoryId($arr[1])
                                ->setTemplate($data['template'])
                                ->toHtml();
                        if ($isAdmin) {
                            $html .= '</div>';
                        }
                        echo $html;
                    }
                } else if ($key == 'featuredProducts' || $key == 'bestsellerProducts' || $key == 'newProducts' || $key == 'topRateProducts' || $key == 'saleProducts') {
                    $arr = explode('=', $value);
                    if (isset($arr[1])) {
                        $html = '';
                        if ($isAdmin) {
                            $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                            $html .= $this->getEditChildInCategory($templateLayout, $blockName, $key, 'core', $currentCategoryId, $currentProductId);
                        }
                        $html .= $this->getLayout()
                                ->createBlock($data['block'])
                                ->setTitle($arr[0])
                                ->setProductsCount($arr[1])
                                ->setTemplate($data['template'])
                                ->toHtml();
                        if ($isAdmin) {
                            $html .= '</div>';
                        }
                        echo $html;
                    }
                } else if ($key == 'facebookLikeBox') {
                    $arr = explode('|', $value);
                    if (count($arr)) {
                        $html = '';
                        if ($isAdmin) {
                            $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                            $html .= $this->getEditChildInCategory($templateLayout, $blockName, $key, 'core', $currentCategoryId, $currentProductId);
                        }
                        $html .= $this->getLayout()
                                ->createBlock($data['block'])
                                ->setTitle($arr[0])
                                ->setPageId($arr[1])
                                ->setConnection($arr[2])
                                ->setWidth($arr[3])
                                ->setHeight($arr[4])
                                ->setShowHeader($arr[5])
                                ->setShowFace($arr[6])
                                ->setShowStream($arr[7])
                                ->setTemplate($data['template'])
                                ->toHtml();
                        if ($isAdmin) {
                            $html .= '</div>';
                        }
                        echo $html;
                    }
                } else if ($key == 'twitterFeed') {
                    $arr = explode('|', $value);
                    if (count($arr)) {
                        $html = '';
                        if ($isAdmin) {
                            $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                            $html .= $this->getEditChildInCategory($templateLayout, $blockName, $key, 'core', $currentCategoryId, $currentProductId);
                        }
                        $html .= $this->getLayout()
                                ->createBlock($data['block'])
                                ->setTitle($arr[0])
                                ->setUser($arr[1])
                                ->setCount($arr[2])
                                ->setTruncate($arr[3])
                                ->setTemplate($data['template'])
                                ->toHtml();
                        if ($isAdmin) {
                            $html .= '</div>';
                        }
                        echo $html;
                    }
                } else {
                    $html = '';
                    if ($isAdmin) {
                        $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                        $html .= $this->getEditChildInCategory($templateLayout, $blockName, $key, 'core', $currentCategoryId, $currentProductId);
                    }
                    $html .= $this->getLayout()
                            ->createBlock($data['block'])
                            ->setTemplate($data['template'])
                            ->toHtml();
                    if ($isAdmin) {
                        $html .= '</div>';
                    }
                    echo $html;
                }
            } else {
                if (strpos($key, 'promoBanner') !== false) {
                    $id = str_replace('promoBanner', '', $key);
                    $promo = Mage::getModel('promobanners/promobanners')->load($id);
                    if ($id && $promo->getId()) {
                        $html = '';
                        if ($isAdmin) {
                            $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                            $html .= $this->getEditChildInCategory($templateLayout, $blockName, $key, 'core', $currentCategoryId, $currentProductId);
                        } else {
                            $html .= '<div class="block block-banner">';
                        }
                        $html .= $this->getLayout()
                                ->createBlock('promobanners/promobanners')
                                ->setBannerId($id)
                                ->setTemplate('mgs/promobanners/banner.phtml')
                                ->toHtml();
                        $html .= '</div>';
                        echo $html;
                    }
                    break;
                }
            }
        }
    }

    public function renderHtmlContentInCms($templateLayout, $blockName, $pageId, $key, $value, $isAdmin) {
        $blocks = array(
            'categoryNavigation' => array(
                'block' => 'mpanel/navigation',
                'template' => 'mgs/mpanel/template/category-navigation.phtml'
            ),
            'cartSidebar' => array(
                'block' => 'checkout/cart_sidebar',
                'template' => 'checkout/cart/sidebar.phtml'
            ),
            'compareSidebar' => array(
                'block' => 'catalog/product_compare_sidebar',
                'template' => 'catalog/product/compare/sidebar.phtml'
            ),
            'reorderSidebar' => array(
                'block' => 'sales/reorder_sidebar',
                'template' => 'sales/reorder/sidebar.phtml'
            ),
            'poll' => array(
                'block' => 'poll/activePoll',
                'poll_template' => array(
                    'poll' => 'poll/active.phtml',
                    'results' => 'poll/result.phtml'
                )
            ),
            'productViewed' => array(
                'block' => 'reports/product_viewed',
                'template' => 'reports/product_viewed.phtml'
            ),
            'wishlistSidebar' => array(
                'block' => 'wishlist/customer_sidebar',
                'template' => 'wishlist/sidebar.phtml'
            ),
            'tagsPopular' => array(
                'block' => 'tag/popular',
                'template' => 'tag/popular.phtml'
            ),
            'newsletter' => array(
                'block' => 'newsletter/subscribe',
                'template' => 'newsletter/subscribe.phtml'
            )
        );
        foreach ($blocks as $block => $data) {
            if ($block == $key) {
                if ($key == 'poll') {
                    $arr = explode('-', $value);
                    if (isset($arr[1])) {
                        $html = '';
                        if ($isAdmin) {
                            $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                            $html .= $this->getEditChildInCms($templateLayout, $blockName, $key, 'core', $pageId);
                        }
                        $poll = $this->getLayout()->createBlock($data['block'])
                                ->setPollId($arr[1]);
                        foreach ($data['poll_template'] as $k => $v) {
                            $poll->setPollTemplate($v, $k);
                        }
                        $html .= $poll->toHtml();
                        if ($isAdmin) {
                            $html .= '</div>';
                        }
                        echo $html;
                    }
                } else if ($key == 'categoryNavigation') {
                    $arr = explode('-', $value);
                    if (isset($arr[1])) {
                        $html = '';
                        if ($isAdmin) {
                            $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                            $html .= $this->getEditChildInCms($templateLayout, $blockName, $key, 'core', $pageId);
                        }
                        $html .= $this->getLayout()
                                ->createBlock($data['block'])
                                ->setSelectedCategoryId($arr[1])
                                ->setTemplate($data['template'])
                                ->toHtml();
                        if ($isAdmin) {
                            $html .= '</div>';
                        }
                        echo $html;
                    }
                } else {
                    $html = '';
                    if ($isAdmin) {
                        $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                        $html .= $this->getEditChildInCms($templateLayout, $blockName, $key, 'core', $pageId);
                    }
                    $html .= $this->getLayout()
                            ->createBlock($data['block'])
                            ->setTemplate($data['template'])
                            ->toHtml();
                    if ($isAdmin) {
                        $html .= '</div>';
                    }
                    echo $html;
                }
            } else {
                if (strpos($key, 'promoBanner') !== false) {
                    $id = str_replace('promoBanner', '', $key);
                    $promo = Mage::getModel('promobanners/promobanners')->load($id);
                    if ($id && $promo->getId()) {
                        $html = '';
                        if ($isAdmin) {
                            $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                            $html .= $this->getEditChildInCms($templateLayout, $blockName, $key, 'core', $pageId);
                        } else {
                            $html .= '<div class="block block-banner">';
                        }
                        $html .= $this->getLayout()
                                ->createBlock('promobanners/promobanners')
                                ->setBannerId($id)
                                ->setTemplate('mgs/promobanners/banner.phtml')
                                ->toHtml();
                        $html .= '</div>';
                        echo $html;
                    }
                    break;
                }
            }
        }
    }

    public function renderHtmlContentInCustomer($templateLayout, $blockName, $key, $value, $isAdmin) {
        $blocks = array(
            'categoryNavigation' => array(
                'block' => 'mpanel/navigation',
                'template' => 'mgs/mpanel/template/category-navigation.phtml'
            ),
            'cartSidebar' => array(
                'block' => 'checkout/cart_sidebar',
                'template' => 'checkout/cart/sidebar.phtml'
            ),
            'compareSidebar' => array(
                'block' => 'catalog/product_compare_sidebar',
                'template' => 'catalog/product/compare/sidebar.phtml'
            ),
            'reorderSidebar' => array(
                'block' => 'sales/reorder_sidebar',
                'template' => 'sales/reorder/sidebar.phtml'
            ),
            'poll' => array(
                'block' => 'poll/activePoll',
                'poll_template' => array(
                    'poll' => 'poll/active.phtml',
                    'results' => 'poll/result.phtml'
                )
            ),
            'productViewed' => array(
                'block' => 'reports/product_viewed',
                'template' => 'reports/product_viewed.phtml'
            ),
            'wishlistSidebar' => array(
                'block' => 'wishlist/customer_sidebar',
                'template' => 'wishlist/sidebar.phtml'
            ),
            'tagsPopular' => array(
                'block' => 'tag/popular',
                'template' => 'tag/popular.phtml'
            ),
            'newsletter' => array(
                'block' => 'newsletter/subscribe',
                'template' => 'newsletter/subscribe.phtml'
            )
        );
        foreach ($blocks as $block => $data) {
            if ($block == $key) {
                if ($key == 'poll') { // Block poll
                    $arr = explode('-', $value);
                    if (isset($arr[1])) {
                        $html = '';
                        if ($isAdmin) {
                            $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                            $html .= $this->getEditChildInCustomer($templateLayout, $blockName, $key, 'core');
                        }
                        $poll = $this->getLayout()->createBlock($data['block'])
                                ->setPollId($arr[1]);
                        foreach ($data['poll_template'] as $k => $v) {
                            $poll->setPollTemplate($v, $k);
                        }
                        $html .= $poll->toHtml();
                        if ($isAdmin) {
                            $html .= '</div>';
                        }
                        echo $html;
                    }
                } else if ($key == 'categoryNavigation') {
                    $arr = explode('-', $value);
                    if (isset($arr[1])) {
                        $html = '';
                        if ($isAdmin) {
                            $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                            $html .= $this->getEditChildInCustomer($templateLayout, $blockName, $key, 'core');
                        }
                        $html .= $this->getLayout()
                                ->createBlock($data['block'])
                                ->setSelectedCategoryId($arr[1])
                                ->setTemplate($data['template'])
                                ->toHtml();
                        if ($isAdmin) {
                            $html .= '</div>';
                        }
                        echo $html;
                    }
                } else { // General block
                    $html = '';
                    if ($isAdmin) {
                        $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                        $html .= $this->getEditChildInCustomer($templateLayout, $blockName, $key, 'core');
                    }
                    $html .= $this->getLayout()
                            ->createBlock($data['block'])
                            ->setTemplate($data['template'])
                            ->toHtml();
                    if ($isAdmin) {
                        $html .= '</div>';
                    }
                    echo $html;
                }
            } else { // if block banner
                if (strpos($key, 'promoBanner') !== false) {
                    $id = str_replace('promoBanner', '', $key);
                    $promo = Mage::getModel('promobanners/promobanners')->load($id);
                    if ($id && $promo->getId()) {
                        $html = '';
                        if ($isAdmin) {
                            $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                            $html .= $this->getEditChildInCustomer($templateLayout, $blockName, $key, 'core');
                        } else {
                            $html .= '<div class="block block-banner">';
                        }
                        $html .= $this->getLayout()
                                ->createBlock('promobanners/promobanners')
                                ->setBannerId($id)
                                ->setTemplate('mgs/promobanners/banner.phtml')
                                ->toHtml();
                        $html .= '</div>';
                        echo $html;
                    }
                    break;
                }
            }
        }
    }

    public function renderHtmlContentInCatalogSearch($templateLayout, $blockName, $key, $value, $isAdmin) {
        $blocks = array(
            'categoryNavigation' => array(
                'block' => 'mpanel/navigation',
                'template' => 'mgs/mpanel/template/category-navigation.phtml'
            ),
            'layeredNavigation' => array(
                'block' => 'catalog/layer_view',
                'template' => 'catalog/layer/view.phtml'
            ),
            'cartSidebar' => array(
                'block' => 'checkout/cart_sidebar',
                'template' => 'checkout/cart/sidebar.phtml'
            ),
            'compareSidebar' => array(
                'block' => 'catalog/product_compare_sidebar',
                'template' => 'catalog/product/compare/sidebar.phtml'
            ),
            'reorderSidebar' => array(
                'block' => 'sales/reorder_sidebar',
                'template' => 'sales/reorder/sidebar.phtml'
            ),
            'poll' => array(
                'block' => 'poll/activePoll',
                'poll_template' => array(
                    'poll' => 'poll/active.phtml',
                    'results' => 'poll/result.phtml'
                )
            ),
            'productViewed' => array(
                'block' => 'reports/product_viewed',
                'template' => 'reports/product_viewed.phtml'
            ),
            'wishlistSidebar' => array(
                'block' => 'wishlist/customer_sidebar',
                'template' => 'wishlist/sidebar.phtml'
            ),
            'tagsPopular' => array(
                'block' => 'tag/popular',
                'template' => 'tag/popular.phtml'
            ),
            'newsletter' => array(
                'block' => 'newsletter/subscribe',
                'template' => 'newsletter/subscribe.phtml'
            ),
            'menu' => array(
                'block' => 'megamenu/vertical',
                'template' => 'megamenu/vertical.phtml'
            ),
            'featuredProducts' => array(
                'block' => 'mpanel/products',
                'template' => 'mgs/mpanel/products/list/featured_products.phtml'
            ),
            'bestsellerProducts' => array(
                'block' => 'mpanel/products',
                'template' => 'mgs/mpanel/products/list/hot_products.phtml'
            ),
            'newProducts' => array(
                'block' => 'mpanel/product_new',
                'template' => 'mgs/mpanel/products/list/new_products.phtml'
            ),
            'topRateProducts' => array(
                'block' => 'mpanel/product_rate',
                'template' => 'mgs/mpanel/products/list/rate_products.phtml'
            ),
            'saleProducts' => array(
                'block' => 'mpanel/product_sale',
                'template' => 'mgs/mpanel/products/list/sale_products.phtml'
            ),
            'facebookLikeBox' => array(
                'block' => 'social/fblikebox',
                'template' => 'mgs/social/facebook-like-box.phtml'
            ),
            'twitterFeed' => array(
                'block' => 'core/template',
                'template' => 'mgs/mpanel/twitter_tweets.phtml'
            )
        );
        foreach ($blocks as $block => $data) {
            if ($block == $key) {
                if ($key == 'poll') { // Block poll
                    $arr = explode('-', $value);
                    if (isset($arr[1])) {
                        $html = '';
                        if ($isAdmin) {
                            $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                            $html .= $this->getEditChildInCatalogSearch($templateLayout, $blockName, $key, 'core');
                        }
                        $poll = $this->getLayout()->createBlock($data['block'])
                                ->setPollId($arr[1]);
                        foreach ($data['poll_template'] as $k => $v) {
                            $poll->setPollTemplate($v, $k);
                        }
                        $html .= $poll->toHtml();
                        if ($isAdmin) {
                            $html .= '</div>';
                        }
                        echo $html;
                    }
                } else if ($key == 'categoryNavigation') {
                    $arr = explode('-', $value);
                    if (isset($arr[1])) {
                        $html = '';
                        if ($isAdmin) {
                            $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                            $html .= $this->getEditChildInCatalogSearch($templateLayout, $blockName, $key, 'core');
                        }
                        $html .= $this->getLayout()
                                ->createBlock($data['block'])
                                ->setSelectedCategoryId($arr[1])
                                ->setTemplate($data['template'])
                                ->toHtml();
                        if ($isAdmin) {
                            $html .= '</div>';
                        }
                        echo $html;
                    }
                } else if ($key == 'menu') {
                    $arr = explode('-', $value);
                    if (isset($arr[1])) {
                        $html = '';
                        if ($isAdmin) {
                            $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                            $html .= $this->getEditChildInCatalogSearch($templateLayout, $blockName, $key, 'core');
                        }
                        $html .= $this->getLayout()
                                ->createBlock($data['block'])
                                ->setMenuId($arr[1])
                                ->setTemplate($data['template'])
                                ->toHtml();
                        if ($isAdmin) {
                            $html .= '</div>';
                        }
                        echo $html;
                    }
                } else if ($key == 'featuredProducts' || $key == 'bestsellerProducts' || $key == 'newProducts' || $key == 'topRateProducts' || $key == 'saleProducts') {
                    $arr = explode('=', $value);
                    if (isset($arr[1])) {
                        $html = '';
                        if ($isAdmin) {
                            $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                            $html .= $this->getEditChildInCatalogSearch($templateLayout, $blockName, $key, 'core');
                        }
                        $html .= $this->getLayout()
                                ->createBlock($data['block'])
                                ->setTitle($arr[0])
                                ->setProductsCount($arr[1])
                                ->setTemplate($data['template'])
                                ->toHtml();
                        if ($isAdmin) {
                            $html .= '</div>';
                        }
                        echo $html;
                    }
                } else if ($key == 'facebookLikeBox') {
                    $arr = explode('|', $value);
                    if (count($arr)) {
                        $html = '';
                        if ($isAdmin) {
                            $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                            $html .= $this->getEditChildInCatalogSearch($templateLayout, $blockName, $key, 'core');
                        }
                        $html .= $this->getLayout()
                                ->createBlock($data['block'])
                                ->setTitle($arr[0])
                                ->setPageId($arr[1])
                                ->setConnection($arr[2])
                                ->setWidth($arr[3])
                                ->setHeight($arr[4])
                                ->setShowHeader($arr[5])
                                ->setShowFace($arr[6])
                                ->setShowStream($arr[7])
                                ->setTemplate($data['template'])
                                ->toHtml();
                        if ($isAdmin) {
                            $html .= '</div>';
                        }
                        echo $html;
                    }
                } else if ($key == 'twitterFeed') {
                    $arr = explode('|', $value);
                    if (count($arr)) {
                        $html = '';
                        if ($isAdmin) {
                            $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                            $html .= $this->getEditChildInCatalogSearch($templateLayout, $blockName, $key, 'core');
                        }
                        $html .= $this->getLayout()
                                ->createBlock($data['block'])
                                ->setTitle($arr[0])
                                ->setUser($arr[1])
                                ->setCount($arr[2])
                                ->setTruncate($arr[3])
                                ->setTemplate($data['template'])
                                ->toHtml();
                        if ($isAdmin) {
                            $html .= '</div>';
                        }
                        echo $html;
                    }
                } else if ($key == 'layeredNavigation') {
                    if (Mage::app()->getRequest()->getModuleName() == 'catalogsearch' && Mage::app()->getRequest()->getControllerName() != 'advanced') {
                        $html = '';
                        if ($isAdmin) {
                            $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                            $html .= $this->getEditChildInCatalogSearch($templateLayout, $blockName, $key, 'core');
                        }
                        $this->getLayout()->getBlock('catalogsearch.leftnav')->toHtml();
                        if ($isAdmin) {
                            $html .= '</div>';
                        }
                        echo $html;
                    }
                } else { // General block
                    $html = '';
                    if ($isAdmin) {
                        $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                        $html .= $this->getEditChildInCatalogSearch($templateLayout, $blockName, $key, 'core');
                    }
                    $html .= $this->getLayout()
                            ->createBlock($data['block'])
                            ->setTemplate($data['template'])
                            ->toHtml();
                    if ($isAdmin) {
                        $html .= '</div>';
                    }
                    echo $html;
                }
            } else { // if block banner
                if (strpos($key, 'promoBanner') !== false) {
                    $id = str_replace('promoBanner', '', $key);
                    $promo = Mage::getModel('promobanners/promobanners')->load($id);
                    if ($id && $promo->getId()) {
                        $html = '';
                        if ($isAdmin) {
                            $html .= '<div class="sort-item builder-container child-builder" id="' . $templateLayout . '_' . $blockName . '_' . $key . '">';
                            $html .= $this->getEditChildInCatalogSearch($templateLayout, $blockName, $key, 'core');
                        } else {
                            $html .= '<div class="block block-banner">';
                        }
                        $html .= $this->getLayout()
                                ->createBlock('promobanners/promobanners')
                                ->setBannerId($id)
                                ->setTemplate('mgs/promobanners/banner.phtml')
                                ->toHtml();
                        $html .= '</div>';
                        echo $html;
                    }
                    break;
                }
            }
        }
    }

    public function getEditTwitterConfig() {
        $html = '<div class="edit-panel child-panel"><ul>';
        $html .= '<li><a href="' . Mage::getUrl('mpanel/edit/twitter') . '" class="popup-link" title="' . $this->__('Edit') . '"><em class="fa fa-edit">&nbsp;</em></a></li>';
        $html .= '</ul></div>';

        return $html;
    }

    // convert col from number of products for responsive
    public function convertColRow($numberOfProduct) {
        switch ($numberOfProduct) {
            case 1:
                $col = 12;
                break;
            case 2:
                $col = 6;
                break;
            case 3:
                $col = 4;
                break;
            case 4:
                $col = 3;
                break;
            case 6:
                $col = 2;
                break;
            default:
                $col = 3;
                break;
        }
        return $col;
    }

    // convert col from number of products for responsive (new)
    public function convertColRowCustom($numberOfProduct) {
        switch ($numberOfProduct) {
            case 1:
                $col = 12;
                break;
            case 2:
                $col = 6;
                break;
            case 3:
                $col = 4;
                break;
            case 4:
                $col = 3;
                break;
            case 5:
                $col = 'custom-5';
                break;
            case 6:
                $col = 2;
                break;
            case 7:
                $col = 'custom-7';
                break;
            case 8:
                $col = 'custom-8';
                break;
            default:
                $col = 3;
                break;
        }
        return $col;
    }

    // change title of product tabs by type of the tab
    public function changeTabTitle($type, $data) {

        $title = '';
        switch ($type) {
            // New Products
            case 'new_products':
                $title = $data['new_label'];
                break;

            // Best Selling Products
            case 'hot_products':
                $title = $data['hot_label'];
                break;

            // Featured Products
            case 'featured_products':
                $title = $data['featured_label'];
                break;

            // Sale Products
            case 'sale_products':
                $title = $data['sale_label'];
                break;

            // Top Rate Products
            case 'rate_products':
                $title = $data['rate_label'];
                break;
        }

        return $title;
    }

    //get theme color
    public function getThemeColor() {
        return array(
            array('value' => 'blue', 'label' => Mage::helper('mpanel')->__('Blue')),
            array('value' => 'light-blue', 'label' => Mage::helper('mpanel')->__('Light Blue')),
            array('value' => 'green', 'label' => Mage::helper('mpanel')->__('Green')),
            array('value' => 'light-green', 'label' => Mage::helper('mpanel')->__('Light Green')),
            array('value' => 'orange', 'label' => Mage::helper('mpanel')->__('Orange')),
            array('value' => 'gold', 'label' => Mage::helper('mpanel')->__('Gold')),
            array('value' => 'purple', 'label' => Mage::helper('mpanel')->__('Purple')),
            array('value' => 'red', 'label' => Mage::helper('mpanel')->__('Red')),
            array('value' => 'tael', 'label' => Mage::helper('mpanel')->__('Tael')),
            array('value' => 'violet', 'label' => Mage::helper('mpanel')->__('Violet')),
            array('value' => 'yellow', 'label' => Mage::helper('mpanel')->__('Yellow')),
            array('value' => 'pink', 'label' => Mage::helper('mpanel')->__('Pink')),
        );
    }

    //get background pattern
    public function getBackgroundPattern() {
        return array(
            array('value' => '', 'label' => ''),
            array('value' => 'gray_jean', 'label' => Mage::helper('mpanel')->__('Gray jean')),
            array('value' => 'linedpaper', 'label' => Mage::helper('mpanel')->__('Linedpaper')),
            array('value' => 'az_subtle', 'label' => Mage::helper('mpanel')->__('Az subtle')),
            array('value' => 'blizzard', 'label' => Mage::helper('mpanel')->__('Blizzard')),
            array('value' => 'denim', 'label' => Mage::helper('mpanel')->__('Denim')),
            array('value' => 'fancy_deboss', 'label' => Mage::helper('mpanel')->__('Fancy deboss')),
            array('value' => 'honey_im_subtle', 'label' => Mage::helper('mpanel')->__('Honey im subtle')),
            array('value' => 'linen', 'label' => Mage::helper('mpanel')->__('Linen')),
            array('value' => 'pw_maze_white', 'label' => Mage::helper('mpanel')->__('Pw maze white')),
            array('value' => 'skin_side_up', 'label' => Mage::helper('mpanel')->__('Skin side up')),
            array('value' => 'stitched_wool', 'label' => Mage::helper('mpanel')->__('Stitched wool')),
            array('value' => 'straws', 'label' => Mage::helper('mpanel')->__('Straws')),
            array('value' => 'subtle_grunge', 'label' => Mage::helper('mpanel')->__('Subtle grunge')),
            array('value' => 'textured_stripes', 'label' => Mage::helper('mpanel')->__('Textured stripes')),
            array('value' => 'wild_oliva', 'label' => Mage::helper('mpanel')->__('Wild oliva')),
            array('value' => 'worn_dots', 'label' => Mage::helper('mpanel')->__('Worn dots')),
            array('value' => 'bright_squares', 'label' => Mage::helper('mpanel')->__('Bright squares')),
            array('value' => 'random_grey_variations', 'label' => Mage::helper('mpanel')->__('Random grey variations')),
        );
    }

    // get include fonts
    public function getFonts() {
        return array(
            array('css-name' => 'Lato', 'font-name' => Mage::helper('mpanel')->__('Lato')),
            array('css-name' => 'Open+Sans', 'font-name' => Mage::helper('mpanel')->__('Open Sans')),
            array('css-name' => 'Roboto', 'font-name' => Mage::helper('mpanel')->__('Roboto')),
            array('css-name' => 'Roboto Slab', 'font-name' => Mage::helper('mpanel')->__('Roboto Slab')),
            array('css-name' => 'Oswald', 'font-name' => Mage::helper('mpanel')->__('Oswald')),
            array('css-name' => 'Source+Sans+Pro', 'font-name' => Mage::helper('mpanel')->__('Source Sans Pro')),
            array('css-name' => 'PT+Sans', 'font-name' => Mage::helper('mpanel')->__('PT Sans')),
            array('css-name' => 'PT+Serif', 'font-name' => Mage::helper('mpanel')->__('PT Serif')),
            array('css-name' => 'Droid+Serif', 'font-name' => Mage::helper('mpanel')->__('Droid Serif')),
            array('css-name' => 'Josefin+Slab', 'font-name' => Mage::helper('mpanel')->__('Josefin Slab')),
            array('css-name' => 'Montserrat', 'font-name' => Mage::helper('mpanel')->__('Montserrat')),
            array('css-name' => 'Ubuntu', 'font-name' => Mage::helper('mpanel')->__('Ubuntu')),
            array('css-name' => 'Titillium+Web', 'font-name' => Mage::helper('mpanel')->__('Titillium Web')),
            array('css-name' => 'Noto+Sans', 'font-name' => Mage::helper('mpanel')->__('Noto Sans')),
            array('css-name' => 'Lora', 'font-name' => Mage::helper('mpanel')->__('Lora')),
            array('css-name' => 'Playfair+Display', 'font-name' => Mage::helper('mpanel')->__('Playfair Display')),
            array('css-name' => 'Bree+Serif', 'font-name' => Mage::helper('mpanel')->__('Bree Serif')),
            array('css-name' => 'Vollkorn', 'font-name' => Mage::helper('mpanel')->__('Vollkorn')),
            array('css-name' => 'Alegreya', 'font-name' => Mage::helper('mpanel')->__('Alegreya')),
            array('css-name' => 'Noto+Serif', 'font-name' => Mage::helper('mpanel')->__('Noto Serif')),
			array('css-name' => 'Poppins', 'font-name' => Mage:: helper('mpanel')->__('Poppins')),
			array('css-name' => 'Libre+Baskerville', 'font-name' => Mage:: helper('mpanel')->__('Libre Baskerville')),
        );
    }

    // get all theme settings
    public function getThemeSettings() {
        $setting = array(
			'lazyload' => Mage::getStoreConfig('mgs_theme/general/lazy_load_img'),
            'enabled' => Mage::getStoreConfig('mpanel/general/enabled'),
            'token' => Mage::getStoreConfig('mpanel/twitter/token'),
            'token_secret' => Mage::getStoreConfig('mpanel/twitter/token_secret'),
            'consumer_key' => Mage::getStoreConfig('mpanel/twitter/consumer_key'),
            'consumer_secret' => Mage::getStoreConfig('mpanel/twitter/consumer_secret'),
            'twitter_title' => Mage::getStoreConfig('mpanel/twitter/twitter_title'),
            'twitter_user' => Mage::getStoreConfig('mpanel/twitter/twitter_user'),
            'twitter_count' => Mage::getStoreConfig('mpanel/twitter/twitter_count'),
            'truncate' => Mage::getStoreConfig('mpanel/twitter/truncate'),
			'apikey' => Mage::getStoreConfig('mpanel/contact/apikey'),
            'enabled_gmap' => Mage::getStoreConfig('mpanel/contact/enabled'),
            'address' => Mage::getStoreConfig('mpanel/contact/address'),
            'html' => Mage::getStoreConfig('mpanel/contact/html'),
            'image' => Mage::getStoreConfig('mpanel/contact/image'),
            'sku' => Mage::getStoreConfig('mpanel/product_details/sku'),
            'email_friend' => Mage::getStoreConfig('mpanel/product_details/email_friend'),
            'reviews_summary' => Mage::getStoreConfig('mpanel/product_details/reviews_summary'),
            'alert_urls' => Mage::getStoreConfig('mpanel/product_details/alert_urls'),
            'wishlist_compare' => Mage::getStoreConfig('mpanel/product_details/wishlist_compare'),
            'short_description' => Mage::getStoreConfig('mpanel/product_details/short_description'),
            'upsell_products' => Mage::getStoreConfig('mpanel/product_details/upsell_products'),
            'brand_image' => Mage::getStoreConfig('mpanel/product_details/brand_image'),
            'brand_products' => Mage::getStoreConfig('mpanel/product_details/brand_products'),
            'page_width' => Mage::getStoreConfig('mgs_theme/general/page_width'),
            'right_to_left' => Mage::getStoreConfig('mgs_theme/general/right_to_left'),
            'layout' => Mage::getStoreConfig('mgs_theme/general/layout'),
            'layout_style' => Mage::getStoreConfig('mgs_theme/general/layout_style'),
            'logo' => Mage::getStoreConfig('mgs_theme/general/logo'),
            'sticky_menu' => Mage::getStoreConfig('mgs_theme/general/sticky_menu'),
            'custom_font' => Mage::getStoreConfig('mgs_theme/fonts/custom_font'),
            'back_to_top' => Mage::getStoreConfig('mgs_theme/general/back_to_top'),
            'preloader' => Mage::getStoreConfig('mgs_theme/general/preloader'),
            'snippets' => Mage::getStoreConfig('mgs_theme/general/snippets'),
            'custom_css' => Mage::getStoreConfig('mgs_theme/general/custom_css'),
            'popup_newsletter' => Mage::getStoreConfig('mgs_theme/general/popup_newsletter'),
            'bg_color' => Mage::getStoreConfig('mgs_theme/background/bg_color'),
            'bg_upload' => Mage::getStoreConfig('mgs_theme/background/bg_upload'),
            'bg_image' => Mage::getStoreConfig('mgs_theme/background/bg_image'),
            'bg_repeat' => Mage::getStoreConfig('mgs_theme/background/bg_repeat'),
            'bg_position_x' => Mage::getStoreConfig('mgs_theme/background/bg_position_x'),
            'bg_position_y' => Mage::getStoreConfig('mgs_theme/background/bg_position_y'),
            'theme_color' => Mage::getStoreConfig('mgs_theme/color/theme_color'),
            'font' => Mage::getStoreConfig('mgs_theme/fonts/font'),
            'h1' => Mage::getStoreConfig('mgs_theme/fonts/h1'),
            'h2' => Mage::getStoreConfig('mgs_theme/fonts/h2'),
            'h3' => Mage::getStoreConfig('mgs_theme/fonts/h3'),
            'h4' => Mage::getStoreConfig('mgs_theme/fonts/h4'),
            'h5' => Mage::getStoreConfig('mgs_theme/fonts/h5'),
            'h6' => Mage::getStoreConfig('mgs_theme/fonts/h6'),
            'price' => Mage::getStoreConfig('mgs_theme/fonts/price'),
            'menu' => Mage::getStoreConfig('mgs_theme/fonts/menu'),
            'megamenu' => Mage::getStoreConfig('megamenu/general/enabled'),
            'ajaxcart' => Mage::getStoreConfig('ajaxcart/general/active'),
            'quickview' => Mage::getStoreConfig('quickview/general/active'),
            'deals' => Mage::getStoreConfig('deals/general/enabled'),
            'oscheckout' => Mage::getStoreConfig('oscheckout/general/enabled'),
            'catalog_layout' => Mage::getStoreConfig('mpanel/catalog/layout'),
            'product_layout' => Mage::getStoreConfig('mpanel/catalog/product_layout'),
            'product_per_row' => Mage::getStoreConfig('mpanel/catalog/product_per_row'),
            'catalog_featured' => Mage::getStoreConfig('mpanel/catalog/featured'),
            'catalog_hot' => Mage::getStoreConfig('mpanel/catalog/hot'),
            'catalog_brands' => Mage::getStoreConfig('mpanel/catalog/brands'),
            'picture_ratio' => Mage::getStoreConfig('mpanel/catalog/picture_ratio'),
            'new_label' => Mage::getStoreConfig('mpanel/catalog/new_label'),
            'sale_label' => Mage::getStoreConfig('mpanel/catalog/sale_label'),
            'price_slider' => Mage::getStoreConfig('mpanel/catalog/price_slider'),
            'more_view' => Mage::getStoreConfig('mpanel/catalog/more_view'),
            'preload' => Mage::getStoreConfig('mpanel/catalog/preload'),
			'hide_add_to_cart_button' => Mage::getStoreConfig('mpanel/catalog/hide_add_to_cart_button'),
            'wishlist_button' => Mage::getStoreConfig('mpanel/catalog/wishlist_button'),
            'compare_button' => Mage::getStoreConfig('mpanel/catalog/compare_button'),
			'breadcrumbactive' => Mage::getStoreConfig('mpanel/breadcrumb/breadcrumbactive'),
			'bgbreadcrumb' => Mage::getStoreConfig('mpanel/breadcrumb/bgbreadcrumb'),
			'breadcrumbcolor' => Mage::getStoreConfig('mpanel/breadcrumb/breadcrumbcolor'),
			'breadcrumbimage' => Mage::getStoreConfig('mpanel/breadcrumb/breadcrumbimage'),
        );
        return $setting;
    }

    public function getBlogSettings() {
        $setting = array(
            'enabled' => Mage::getStoreConfig('blog/blog/enabled'),
            'title' => Mage::getStoreConfig('blog/blog/title'),
            'keywords' => Mage::getStoreConfig('blog/blog/keywords'),
            'description' => Mage::getStoreConfig('blog/blog/description'),
            'layout' => Mage::getStoreConfig('blog/blog/layout'),
            'dateformat' => Mage::getStoreConfig('blog/blog/dateformat'),
            'blogcrumbs' => Mage::getStoreConfig('blog/blog/blogcrumbs'),
            'readmore' => Mage::getStoreConfig('blog/blog/readmore'),
            'useshortcontent' => Mage::getStoreConfig('blog/blog/useshortcontent'),
            'parse_cms' => Mage::getStoreConfig('blog/blog/parse_cms'),
            'perpage' => Mage::getStoreConfig('blog/blog/perpage'),
            'bookmarkspost' => Mage::getStoreConfig('blog/blog/bookmarkspost'),
            'bookmarkslist' => Mage::getStoreConfig('blog/blog/bookmarkslist'),
            'categories_urls' => Mage::getStoreConfig('blog/blog/categories_urls'),
            'sorter' => Mage::getStoreConfig('blog/blog/sorter'),
            'left' => Mage::getStoreConfig('blog/menu/left'),
            'right' => Mage::getStoreConfig('blog/menu/right'),
            'footer' => Mage::getStoreConfig('blog/menu/footer'),
            'top' => Mage::getStoreConfig('blog/menu/top'),
            'category' => Mage::getStoreConfig('blog/menu/category'),
            'tagcloud_size' => Mage::getStoreConfig('blog/menu/tagcloud_size'),
            'recent' => Mage::getStoreConfig('blog/menu/recent'),
            'comments_enabled' => Mage::getStoreConfig('blog/comments/enabled'),
            'login' => Mage::getStoreConfig('blog/comments/login'),
            'approval' => Mage::getStoreConfig('blog/comments/approval'),
            'loginauto' => Mage::getStoreConfig('blog/comments/loginauto'),
            'recipient_email' => Mage::getStoreConfig('blog/comments/recipient_email'),
            'sender_email_identity' => Mage::getStoreConfig('blog/comments/sender_email_identity'),
            'email_template' => Mage::getStoreConfig('blog/comments/email_template'),
            'page_count' => Mage::getStoreConfig('blog/comments/page_count'),
			
        );
        return $setting;
    }

    // get one step checkout settings
    public function getCheckoutSettings() {
        $setting = array(
            'checkout_title' => Mage::getStoreConfig('oscheckout/general/checkout_title'),
            'checkout_link' => Mage::getStoreConfig('oscheckout/general/checkout_link'),
            'guest_checkout' => Mage::getStoreConfig('oscheckout/registration/guest_checkout'),
            'company' => Mage::getStoreConfig('oscheckout/display/company'),
            'telephone' => Mage::getStoreConfig('oscheckout/display/telephone'),
            'fax' => Mage::getStoreConfig('oscheckout/display/fax'),
            'address' => Mage::getStoreConfig('oscheckout/display/address'),
            'discount' => Mage::getStoreConfig('oscheckout/display/discount'),
            'terms_enabled' => Mage::getStoreConfig('oscheckout/terms/enabled'),
            'terms_title' => Mage::getStoreConfig('oscheckout/terms/title'),
            'terms_label' => Mage::getStoreConfig('oscheckout/terms/label'),
            'terms_contents' => Mage::getStoreConfig('oscheckout/terms/contents'),
            'comment_enabled' => Mage::getStoreConfig('oscheckout/comment/enabled'),
            'comment_title' => Mage::getStoreConfig('oscheckout/comment/title'),
            'show_grid' => Mage::getStoreConfig('oscheckout/comment/show_grid'),
        );
        return $setting;
    }

    // get product questions settings
    public function getFaqsSettings() {
        $setting = array(
            'who_ask' => Mage::getStoreConfig('productquestions/general/who_ask'),
            'who_answer' => Mage::getStoreConfig('productquestions/general/who_answer'),
            'automatic' => Mage::getStoreConfig('productquestions/general/automatic'),
            'rate' => Mage::getStoreConfig('productquestions/general/rate'),
            'who_rate' => Mage::getStoreConfig('productquestions/general/who_rate'),
            'visibility' => Mage::getStoreConfig('productquestions/general/visibility'),
            'active' => Mage::getStoreConfig('productquestions/question_email/active'),
            'notification' => Mage::getStoreConfig('productquestions/question_email/notification'),
            'admin_email' => Mage::getStoreConfig('productquestions/question_email/admin_email'),
            'email_sender' => Mage::getStoreConfig('productquestions/question_email/email_sender'),
            'admin_question_template' => Mage::getStoreConfig('productquestions/question_email/admin_question_template'),
            'admin_answer_template' => Mage::getStoreConfig('productquestions/question_email/admin_answer_template'),
            'question_template' => Mage::getStoreConfig('productquestions/question_email/question_template'),
            'answer_template' => Mage::getStoreConfig('productquestions/question_email/answer_template'),
            'title' => Mage::getStoreConfig('productquestions/faqs_page/title'),
            'url_key' => Mage::getStoreConfig('productquestions/faqs_page/url_key'),
            'faqs_link_to_toplink' => Mage::getStoreConfig('productquestions/faqs_page/faqs_link_to_toplink'),
            'meta_keywords' => Mage::getStoreConfig('productquestions/faqs_page/meta_keywords'),
            'meta_description' => Mage::getStoreConfig('productquestions/faqs_page/meta_description'),
            'accordition' => Mage::getStoreConfig('productquestions/faqs_page/accordition'),
            'sort_by' => Mage::getStoreConfig('productquestions/faqs_page/sort_by'),
            'block_active' => Mage::getStoreConfig('productquestions/faqs_block/active'),
            'block_title' => Mage::getStoreConfig('productquestions/faqs_block/block_title'),
            'number_of_topics' => Mage::getStoreConfig('productquestions/faqs_block/number_of_topics'),
            'enabled' => Mage::getStoreConfig('productquestions/recaptcha/enabled'),
            'public_key' => Mage::getStoreConfig('productquestions/recaptcha/public_key'),
            'private_key' => Mage::getStoreConfig('productquestions/recaptcha/private_key'),
            'theme' => Mage::getStoreConfig('productquestions/recaptcha/theme'),
            'lang' => Mage::getStoreConfig('productquestions/recaptcha/lang'),
        );
        return $setting;
    }

    public function getFileByType($type) {
        $theme = '';

        if (Mage::app()->getStore()->isAdmin()) {
            if (Mage::app()->getRequest()->getParam('store') || Mage::app()->getRequest()->getParam('website')) {
                if ($storeCode = Mage::app()->getRequest()->getParam('store')) {
                    $store = Mage::getModel("core/store")->load($storeCode);
                    $storeId = $store->getId();
                    $theme = Mage::getStoreConfig('design/theme/default', $storeId);
                } else {
                    if ($websiteCode = Mage::app()->getRequest()->getParam('website')) {
                        $website = Mage::getModel("core/website")->load($websiteCode);
                        $theme = Mage::app()->getWebsite($website)->getConfig('design/theme/default');
                    }
                }
            } else {
                $theme = Mage::app()->getWebsite(0)->getConfig('design/theme/default');
            }
        } else {
            $storeId = Mage::app()->getStore()->getId();
            $theme = Mage::getStoreConfig('design/theme/default', $storeId);
        }

        $result = array();

        if(Mage::getStoreConfig('mpanel/general/themepack')) {
			$dir = Mage::getBaseDir() . '/skin/frontend/'.Mage::getStoreConfig('mpanel/general/themepack').'/'.$theme.'/asset/' . $type . '/';
		} else {
			$dir = Mage::getBaseDir() . '/skin/frontend/mgstheme/' . $theme . '/asset/' . $type . '/';
		}
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                $i = 0;
                while (($file = readdir($dh)) !== false) {
                    $file_parts = pathinfo($dir . $file);
                    if (isset($file_parts['extension']) && $file_parts['extension'] == 'jpg') {
                        $i++;
                        $fileName = str_replace('.jpg', '', $file);
                        $result[] = array('value' => $fileName, 'label' => Mage::helper('mpanel')->__('Version %s', $i));
                    }
                }
                closedir($dh);
            }
        }

        return $result;
    }

    // get header versions for config
    public function getHeaderVersion() {
        return $this->getFileByType('headers');
    }

    // get footer versions for config
    public function getFooterVersion() {
        return $this->getFileByType('footers');
    }

    // get first image from another content
    public function getFirstImage($content, $url) {
        $html = '';

        $helper = Mage::helper('cms');
        $processor = $helper->getPageTemplateProcessor();
        $content = $processor->filter($content);

        $first_img = '';
        $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);

        if (isset($matches[1][0])) {
            $first_img = $matches[1][0];
            $html = '<div class="blog-image entry"><a href="' . $url . '"><img class="img-responsive" alt="" src="' . $first_img . '"/></a></div>';
        }
        return $html;
    }

    public function getPageObject() {
        $result = array();
        if (Mage::app()->getRequest()->getModuleName() == 'deals') {
            $result = array(
                'type' => 'store_config',
                'id' => 'deals',
                'page_title' => Mage::getStoreConfig('deals/deals_page/title'),
                'meta_keywords' => Mage::getStoreConfig('deals/deals_page/meta_keyword'),
                'meta_description' => Mage::getStoreConfig('deals/deals_page/meta_description'),
            );
        } else {
            if (!$this->isCatalogPage()) {
                $cmsPageUrlKey = Mage::getSingleton('cms/page')->getIdentifier();
                $page = Mage::getBlockSingleton('cms/page')->getPage();
                $result = array(
                    'type' => 'page_id',
                    'id' => $page->getId(),
                    'page_title' => $page->getTitle(),
                    'meta_keywords' => $page->getMetaKeywords(),
                    'meta_description' => $page->getMetaDescription(),
                );
            } else {
                if ($this->isCategoryPage()) {
                    $category = Mage::registry('current_category');
                    $result = array(
                        'type' => 'category_id',
                        'id' => $category->getId(),
                        'page_title' => $category->getMetaTitle(),
                        'meta_keywords' => $category->getMetaKeywords(),
                        'meta_description' => $category->getMetaDescription()
                    );
                } else {
                    $product = Mage::registry('current_product');
                    $result = array(
                        'type' => 'product_id',
                        'id' => $product->getId(),
                        'page_title' => $product->getMetaTitle(),
                        'meta_keywords' => $product->getMetaKeyword(),
                        'meta_description' => $product->getMetaDescription()
                    );
                }
            }
        }
        return $result;
    }

    public function getBlockIdByIndentifier($indetifier) {
        $storeId = Mage::app()->getStore()->getId();

        $collection = Mage::getModel('cms/block')->getCollection();
        $storeTable = Mage::getSingleton('core/resource')->getTableName('cms_block_store');
        $collection->getSelect()
                ->joinLeft(array('store' => $storeTable), 'main_table.block_id =store.block_id', array('store.store_id'))
                ->where('identifier="' . $indetifier . '"')
                ->where('store_id IN (?)', array(0, $storeId))
                ->order('store_id DESC');
        return $collection->getFirstItem()->getId();
    }

    public function convertRatioToSize($customRatio = NULL) {
        $ratio = Mage::getStoreConfig('mpanel/catalog/picture_ratio');
        if ($customRatio && $customRatio != '') {
            $ratio = $customRatio;
        }

        if ($this->isCategoryPage()) {
            $catId = Mage::app()->getRequest()->getParam('id');
            $setting = Mage::getModel('mpanel/setting')->load($catId);
            if ($setting) {
                if ($setting->getRatio() != '') {
                    $ratio = $setting->getRatio();
                }
            }
        }

        if (Mage::app()->getRequest()->getModuleName() == 'catalogsearch') {
            if (Mage::getStoreConfig('mpanel/catalogsearch/picture_ratio') != '') {
                $ratio = Mage::getStoreConfig('mpanel/catalogsearch/picture_ratio');
            }
        }
		
		$maxWidth = Mage::getStoreConfig('mpanel/catalog/max_width_image');

        $result = array();
        switch ($ratio) {
            // 1/1 Square
            case 1:
                $result = array('width' => round($maxWidth), 'height' => round($maxWidth));
                break;
            // 1/2 Portrait
            case 2:
                $result = array('width' => round($maxWidth), 'height' => round($maxWidth*2));
                break;
            // 2/3 Portrait
            case 3:
                $result = array('width' => round($maxWidth), 'height' => round(($maxWidth * 1.5)));
                break;
            // 3/4 Portrait
            case 4:
                $result = array('width' => round($maxWidth), 'height' => round(($maxWidth * 4) / 3));
                break;
            // 2/1 Landscape
            case 5:
                $result = array('width' => round($maxWidth), 'height' => round($maxWidth/2));
                break;
            // 3/2 Landscape
            case 6:
                $result = array('width' => round($maxWidth), 'height' => round(($maxWidth*2) / 3));
                break;
            // 4/3 Landscape
            case 7:
                $result = array('width' => round($maxWidth), 'height' => round(($maxWidth*3) / 4));
                break;
        }

        return $result;
    }
	
	/* Get product image padding */
	public function getImagePadding(){
		
		$ratio = Mage::getStoreConfig('mpanel/catalog/picture_ratio');
		
		$result = "";
        switch ($ratio) {
            // 1/1 Square
            case 1:
                $result = 100;
                break;
            // 1/2 Portrait
            case 2:
                $result = 200;
                break;
            // 2/3 Portrait
            case 3:
                $result = 150;
                break;
            // 3/4 Portrait
            case 4:
				$value = (400 / 3);
                $result = round($value, 4);
                break;
            // 2/1 Landscape
            case 5:
                $result = 50;
                break;
            // 3/2 Landscape
            case 6:
				$value = (200 / 3);
                $result = round($value, 4);
                break;
            // 4/3 Landscape
            case 7:
                $result = 75;
                break;
        }
		
		$result .= "%";

        return $result;
	}
	
	public function convertRatioToDetailSize() {
        $ratio = Mage::getStoreConfig('mpanel/catalog/picture_ratio');
		
		$maxWidth = Mage::getStoreConfig('mpanel/catalog/max_width_image_detail');

        $result = array();
        switch ($ratio) {
            // 1/1 Square
            case 1:
                $result = array('width' => round($maxWidth), 'height' => round($maxWidth));
                break;
            // 1/2 Portrait
            case 2:
                $result = array('width' => round($maxWidth), 'height' => round($maxWidth*2));
                break;
            // 2/3 Portrait
            case 3:
                $result = array('width' => round($maxWidth), 'height' => round(($maxWidth * 1.5)));
                break;
            // 3/4 Portrait
            case 4:
                $result = array('width' => round($maxWidth), 'height' => round(($maxWidth * 4) / 3));
                break;
            // 2/1 Landscape
            case 5:
                $result = array('width' => round($maxWidth), 'height' => round($maxWidth/2));
                break;
            // 3/2 Landscape
            case 6:
                $result = array('width' => round($maxWidth), 'height' => round(($maxWidth*2) / 3));
                break;
            // 4/3 Landscape
            case 7:
                $result = array('width' => round($maxWidth), 'height' => round(($maxWidth*3) / 4));
                break;
        }

        return $result;
    }

    public function convertRatioToMinSize($customRatio = NULL) {
        $ratio = Mage::getStoreConfig('mpanel/catalog/picture_ratio');

        if ($customRatio && $customRatio != '') {
            $ratio = $customRatio;
        }

        $result = array();
        switch ($ratio) {
            // 1/1 Square
            case 1:
                $result = array('width' => 120, 'height' => 120);
                break;
            // 1/2 Portrait
            case 2:
                $result = array('width' => 120, 'height' => 240);
                break;
            // 2/3 Portrait
            case 3:
                $result = array('width' => 120, 'height' => 180);
                break;
            // 3/4 Portrait
            case 4:
                $result = array('width' => 120, 'height' => 160);
                break;
            // 2/1 Landscape
            case 5:
                $result = array('width' => 120, 'height' => 60);
                break;
            // 3/2 Landscape
            case 6:
                $result = array('width' => 120, 'height' => 80);
                break;
            // 4/3 Landscape
            case 7:
                $result = array('width' => 120, 'height' => 90);
                break;
        }

        return $result;
    }

    // get product label html
    public function getProductLabel($product, $new = NULL, $sale = NULL) {
        $newLabel = Mage::getStoreConfig('mpanel/catalog/new_label');
        $saleLabel = Mage::getStoreConfig('mpanel/catalog/sale_label');
		$soldLabel = $this->__('Out of Stock');
		$html = '';
		
		// Out of stock label
		if (!$product->isSaleable()){
			$html .= '<span class="product-label sold-out-label"><span>'.$soldLabel.'</span></span>';
		}else {
			$now = Mage::getModel('core/date')->date('Y-m-d H:i:s');
			$newFromDate = $product->getNewsFromDate();
			$newFromDate = date("Y-m-d H:i:s", strtotime($newFromDate));
			$newToDate = $product->getNewsToDate();
			$newToDate = date("Y-m-d H:i:s", strtotime($newToDate));
			$numberLabel = 0;
			if ($new && $newLabel != '') {
				$html.='<div class="product-label new-label"><span class="new">' . $newLabel . '</span></div>';
				$numberLabel = 1;
			} else {
				if (!(empty($newToDate) && empty($newFromDate)) && ($newFromDate < $now || empty($newFromDate)) && ($newToDate > $now || empty($newToDate)) && ($newLabel != '')) {
					$html.='<div class="product-label new-label"><span class="new">' . $newLabel . '</span></div>';
					$numberLabel = 1;
				}
			}

			$specialPrice = number_format($product->getFinalPrice(), 2);
			$regularPrice = number_format($product->getPrice(), 2);
			if ($sale && $saleLabel != '') {
				if($numberLabel == 1){
					$html.='<div class="product-label sale-label multiple-label"><span class="sale">' . $saleLabel . '</span></div>';
				}else{
					$html.='<div class="product-label sale-label"><span class="sale">' . $saleLabel . '</span></div>';
				}
			} else {
				if (($specialPrice != $regularPrice) && ($saleLabel != '')) {
					if($numberLabel == 1){
						$html.='<div class="product-label sale-label multiple-label"><span class="sale">' . $saleLabel . '</span></div>';
					}else{
						$html.='<div class="product-label sale-label"><span class="sale">' . $saleLabel . '</span></div>';
					}
				}
			}
		}
        return $html;
    }

    public function getExistCmsStatic($type) {
        $storeId = Mage::app()->getStore()->getId();

        $collection = Mage::getModel('cms/block')->getCollection();
        $storeTable = Mage::getSingleton('core/resource')->getTableName('cms_block_store');

        $childCollection = Mage::getModel('mpanel/childs')->getCollection()
                ->addFieldToSelect('static_block_id');

        if ($type != 'NOT IN') {
            $childCollection->addFieldToFilter('type', 'static');
        }
        $childCollection->getSelect()->distinct(true);

        $arrExist = array();
        if (count($childCollection) > 0) {
            foreach ($childCollection as $_child) {
                $arrExist[] = $_child->getStaticBlockId();
            }
        }

        if (count($arrExist) > 0) {
            $collection->getSelect()
                    ->joinLeft(array('store' => $storeTable), 'main_table.block_id =store.block_id', array('store.store_id'))
                    ->where('store.store_id IN (?)', array(0, $storeId))
                    ->where('main_table.block_id ' . $type . ' (?)', $arrExist)
                    ->order('store.store_id DESC');
        }
        return $collection;
    }

    public function getAllStatic() {
        $storeId = Mage::app()->getStore()->getId();

        $collection = Mage::getModel('cms/block')->getCollection();
        $storeTable = Mage::getSingleton('core/resource')->getTableName('cms_block_store');

        if (count($arrExist) > 0) {
            $collection->getSelect()
                    ->joinLeft(array('store' => $storeTable), 'main_table.block_id =store.block_id', array('store.store_id'))
                    ->where('store.store_id IN (?)', array(0, $storeId))
                    ->order('store.store_id DESC');
        }
        return $collection;
    }

    public function getCol($perrow = NULL) {
        if($perrow == ""){
			$perrow = Mage::getStoreConfig('mpanel/catalog/product_per_row');
			
			if ($this->isCategoryPage()) {
				$catId = Mage::app()->getRequest()->getParam('id');
				$setting = Mage::getModel('mpanel/setting')->load($catId);
				if ($setting) {
					if ($setting->getNumberProductOnRow() != '') {
						$perrow = $setting->getNumberProductOnRow();
					}
				}
			}
			
			if (Mage::app()->getRequest()->getModuleName() == 'catalogsearch') {
				if (Mage::getStoreConfig('mpanel/catalogsearch/product_per_row') != '') {
					$perrow = Mage::getStoreConfig('mpanel/catalogsearch/product_per_row');
				}
			}
		}

        switch ($perrow) {
            case 2:
                return '6';
                break;
            case 3:
                return '4';
                break;
            case 4:
                return '3';
                break;
            case 5:
                return 'custom-5';
                break;
            case 6:
                return '2';
                break;
            case 7:
                return 'custom-7';
                break;
            case 8:
                return 'custom-8';
                break;
        }
    }
	
	public function getColClass($perrow = NULL) {
		if($perrow == ""){
			$perrow = Mage::getStoreConfig('mpanel/catalog/product_per_row');
			
			if ($this->isCategoryPage()) {
				$catId = Mage::app()->getRequest()->getParam('id');
				$setting = Mage::getModel('mpanel/setting')->load($catId);
				if ($setting) {
					if ($setting->getNumberProductOnRow() != '') {
						$perrow = $setting->getNumberProductOnRow();
					}
				}
			}
			
			if (Mage::app()->getRequest()->getModuleName() == 'catalogsearch') {
				if (Mage::getStoreConfig('mpanel/catalogsearch/product_per_row') != '') {
					$perrow = Mage::getStoreConfig('mpanel/catalogsearch/product_per_row');
				}
			}
		}
        switch ($perrow) {
            case 2:
                return 'col-md-6 col-xs-6';
                break;
            case 3:
                return 'col-md-4 col-sm-4 col-xs-6';
                break;
            case 4:
                return 'col-md-3 col-sm-4 col-xs-6';
                break;
            case 5:
                return 'col-md-custom-5 col-sm-4 col-xs-6';
                break;
            case 6:
                return 'col-md-2 col-sm-3 col-xs-6';
                break;
            case 7:
                return 'col-md-custom-7 col-sm-3 col-xs-6';
                break;
            case 8:
                return 'col-md-custom-8 col-sm-6 col-xs-6';
                break;
        }
    }
	//
	public function getClearClass($perrow = NULL, $position) {
        if($perrow == ""){
			$perrow = Mage::getStoreConfig('mpanel/catalog/product_per_row');
			
			if ($this->isCategoryPage()) {
				$catId = Mage::app()->getRequest()->getParam('id');
				$setting = Mage::getModel('mpanel/setting')->load($catId);
				if ($setting) {
					if ($setting->getNumberProductOnRow() != '') {
						$perrow = $setting->getNumberProductOnRow();
					}
				}
			}
			
			if (Mage::app()->getRequest()->getModuleName() == 'catalogsearch') {
				if (Mage::getStoreConfig('mpanel/catalogsearch/product_per_row') != '') {
					$perrow = Mage::getStoreConfig('mpanel/catalogsearch/product_per_row');
				}
			}
		}
		$class = "";
        switch ($perrow) {
            case 2:
				if($position % 2 == 1){
					$class .= " first-row-item first-sm-item first-xs-item";
				}
                break;
            case 3:
				if($position % 3 == 1){
					$class .= " first-row-item first-sm-item";
				}
				if($position % 2 == 1){
					$class .= " first-xs-item";
				}
                break;
            case 4:
				if($position % 4 == 1){
					$class .= " first-row-item";
				}
				if($position % 3 == 1){
					$class .= " first-sm-item";
				}
				if($position % 2 == 1){
					$class .= " first-xs-item";
				}
                break;
            case 5:
				if($position % 5 == 1){
					$class .= " first-row-item";
				}
				if($position % 3 == 1){
					$class .= " first-sm-item";
				}
				if($position % 2 == 1){
					$class .= " first-xs-item";
				}
                break;
            case 6:
				if($position % 6 == 1){
					$class .= " first-row-item";
				}
				if($position % 4 == 1){
					$class .= " first-sm-item";
				}
				if($position % 2 == 1){
					$class .= " first-xs-item";
				}
                break;
            case 7:
				if($position % 7 == 1){
					$class .= " first-row-item";
				}
				if($position % 4 == 1){
					$class .= " first-sm-item";
				}
				if($position % 2 == 1){
					$class .= " first-xs-item";
				}
                break;
            case 8:
				if($position % 8 == 1){
					$class .= " first-row-item";
				}
				if($position % 4 == 1){
					$class .= " first-sm-item";
				}
				if($position % 2 == 1){
					$class .= " first-xs-item";
				}
                break;
        }
		
		return $class;
    }

    // get all action of my account page
    public function getMyAccountActionName() {
        return array(
            'customer_account_index',
            'customer_account_edit',
            'customer_address_index',
            'customer_address_form',
            'sales_order_history',
            'sales_order_view',
            'sales_billing_agreement_index',
            'sales_recurring_profile_index',
            'review_customer_index',
            'review_customer_view',
            'tag_customer_index',
            'wishlist_index_index',
            'oauth_customer_token_index',
            'newsletter_manage_index',
            'downloadable_customer_products',
        );
    }

    public function getAnimationClass() {
        return array(
            array('label' => $this->__('Choose Animation Effect'), 'value' => ''),
            array('label' => 'bounce', 'value' => 'bounce'),
            array('label' => 'flash', 'value' => 'flash'),
            array('label' => 'pulse', 'value' => 'pulse'),
            array('label' => 'rubberBand', 'value' => 'rubberBand'),
            array('label' => 'shake', 'value' => 'shake'),
            array('label' => 'swing', 'value' => 'swing'),
            array('label' => 'tada', 'value' => 'tada'),
            array('label' => 'wobble', 'value' => 'wobble'),
            array('label' => 'bounceIn', 'value' => 'bounceIn'),
            // array('label'=>'bounceInDown','value'=>'bounceInDown'),
            // array('label'=>'bounceInLeft','value'=>'bounceInLeft'),
            // array('label'=>'bounceInRight','value'=>'bounceInRight'),
            // array('label'=>'bounceInUp','value'=>'bounceInUp'),
            array('label' => 'fadeIn', 'value' => 'fadeIn'),
            array('label' => 'fadeInDown', 'value' => 'fadeInDown'),
            array('label' => 'fadeInDownBig', 'value' => 'fadeInDownBig'),
            array('label' => 'fadeInLeft', 'value' => 'fadeInLeft'),
            array('label' => 'fadeInLeftBig', 'value' => 'fadeInLeftBig'),
            array('label' => 'fadeInRight', 'value' => 'fadeInRight'),
            array('label' => 'fadeInRightBig', 'value' => 'fadeInRightBig'),
            array('label' => 'fadeInUp', 'value' => 'fadeInUp'),
            array('label' => 'fadeInUpBig', 'value' => 'fadeInUpBig'),
            array('label' => 'flip', 'value' => 'flip'),
            array('label' => 'flipInX', 'value' => 'flipInX'),
            array('label' => 'flipInY', 'value' => 'flipInY'),
            array('label' => 'lightSpeedIn', 'value' => 'lightSpeedIn'),
            array('label' => 'rotateIn', 'value' => 'rotateIn'),
            array('label' => 'rotateInDownLeft', 'value' => 'rotateInDownLeft'),
            array('label' => 'rotateInDownRight', 'value' => 'rotateInDownRight'),
            array('label' => 'rotateInUpLeft', 'value' => 'rotateInUpLeft'),
            array('label' => 'rotateInUpRight', 'value' => 'rotateInUpRight'),
            //array('label'=>'hinge','value'=>'hinge'),
            array('label' => 'rollIn', 'value' => 'rollIn'),
            array('label' => 'zoomIn', 'value' => 'zoomIn'),
            array('label' => 'zoomInDown', 'value' => 'zoomInDown'),
            array('label' => 'zoomInLeft', 'value' => 'zoomInLeft'),
            array('label' => 'zoomInRight', 'value' => 'zoomInRight'),
            array('label' => 'zoomInUp', 'value' => 'zoomInUp')
        );
    }

    public function isCategoryActive($category) {
        if ($id = Mage::app()->getRequest()->getParam('id')) {
            $child = Mage::getModel('mpanel/childs')->load($id);
            $settings = json_decode($child->getSetting(), true);

            if ($settings['category_id'] != 0) {
                //$currentCategory = Mage::getModel('catalog/category')->load($settings['category_id']);
                return in_array($category->getId(), $settings['category_id']);
            }
            return false;
        }
        return false;
    }

    public function getCategoryCollection() {
        $collection = $this->getData('category_collection');
        if (is_null($collection)) {
            $collection = Mage::getModel('catalog/category')->getCollection();

            /* @var $collection Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection */
            $collection->addAttributeToSelect('name');
        }
        return $collection;
    }

    public function getTreeCategory($category, $parent, $ids = array()) {
        $rootCategoryId = Mage::app()->getStore(Mage::app()->getRequest()->getParam('store'))->getRootCategoryId();

        $categoryIds = array();

        if ($id = Mage::app()->getRequest()->getParam('id')) {

            $child = Mage::getModel('mpanel/childs')->load($id);
            $settings = json_decode($child->getSetting(), true);

            if ($settings['category_id'] != 0) {
                $categoryIds = $settings['category_id'];
            }
        }

        $children = $category->getChildrenCategories();
        $childrenCount = count($children);

        $htmlLi = '<li>';
        $html[] = $htmlLi;
        if ($this->isCategoryActive($category)) {
            $ids[] = $category->getId();
            $this->_ids = implode(",", $ids);
        }

        $html[] = '<a id="node' . $category->getId() . '">';

        if ($rootCategoryId != $category->getId()) {
            $html[] = '<input lang="' . $category->getId() . '" type="checkbox" id="radio' . $category->getId() . '" name="setting[category_id][]" value="' . $category->getId() . '" class="validate-one-required-by-name radio checkbox' . $parent . '"';

            if (is_array($categoryIds)) {
                if (in_array($category->getId(), $categoryIds)) {
                    $html[] = ' checked="checked"';
                }
            } else {
                if ($category->getId() == $categoryIds) {
                    $html[] = ' checked="checked"';
                }
            }
            $html[] = '/>';
        }

        $html[] = '<label for="radio' . $category->getId() . '">' . $category->getName() . '</label>';

        $html[] = '</a>';

        $htmlChildren = '';
        if ($childrenCount > 0) {
            foreach ($children as $child) {

                $_child = Mage::getModel('catalog/category')->load($child->getId());
                $htmlChildren .= $this->getTreeCategory($_child, $category->getId(), $ids);
            }
        }
        if (!empty($htmlChildren)) {
            $html[] = '<ul id="container' . $category->getId() . '">';
            $html[] = $htmlChildren;
            $html[] = '</ul>';
        }

        $html[] = '</li>';
        $html = implode("\n", $html);
        return $html;
    }

    public function hasDifferentLayout() {
        $id = Mage::app()->getRequest()->getParam('id');
        $collection = Mage::getModel('mpanel/layout')
                ->getCollection()
                ->addFieldToFilter('page_type', 'category')
                ->addFieldToFilter('indentifier', $id);
        if (count($collection) > 0) {
            return true;
        }
        return false;
    }

    public function getCategoryProductLayout() {
        $theme = Mage::getSingleton('core/design_package')->getTheme('frontend');

        $dir = Mage::getBaseDir() . '/app/design/frontend/'.Mage::getStoreConfig('mpanel/general/themepack').'/' . $theme . '/template/mgs/mpanel/products/category_products/';
        $arrLayout = array();
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    $file_parts = pathinfo($dir . $file);
                    if (isset($file_parts['extension']) && $file_parts['extension'] == 'phtml') {
                        $layout = $_layout = str_replace('.phtml', '', $file);
                        $layout = $this->convertThemeName($layout);
                        $arrLayout[$file] = $layout;
                    }
                }
            }
        }
        return $arrLayout;
    }

    public function convertThemeName($theme) {
        $themeName = str_replace('_', ' ', $theme);
        return ucfirst($themeName);
    }

    public function getContentOfBlock($block) {
        switch ($block->getType()) {
            case 'separator':
                return $this->getLayout()->createBlock('core/template')->setBlockData($block)->setTemplate('mgs/mpanel/template/separator.phtml')->toHtml();
                break;
            case 'static':
                return $this->getLayout()->createBlock('cms/block')->setBlockId($block->getStaticBlockId())->toHtml();
                break;
            default:
                $helper = Mage::helper('cms');
                $processor = $helper->getPageTemplateProcessor();
				//echo "<pre>"; print_r($block->getBlockContent());			
                return $processor->filter($block->getBlockContent());
                break;
        }
    }

    public function getBlockCols() {
        return array(
            '12' => '12.png',
            '6,6' => '6-6.png',
            '4,4,4' => '4-4-4.png',
            '3,3,3,3' => '3-3-3-3.png',
            '3,9' => '3-9.png',
            '9,3' => '9-3.png',
            '4,8' => '4-8.png',
            '8,4' => '8-4.png',
        );
    }

    public function getColorAccept($type, $color = NULL) {
		$themepack = Mage::getStoreConfig('mpanel/general/themepack');
		if($themepack == ''){
			$themepack = 'mgstheme';
		}
        $dir = Mage::getBaseDir() . '/skin/frontend/'.$themepack.'/default/asset/colour/';
        $html = '';
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                $html .= '<ul>';

                while ($files[] = readdir($dh));
                sort($files);
                foreach ($files as $file) {
                    $file_parts = pathinfo($dir . $file);
                    if (isset($file_parts['extension']) && $file_parts['extension'] == 'png') {
                        $colour = str_replace('.png', '', $file);
                        $wrapper = str_replace('_', '-', $type);
                        $colour = $wrapper . '-' . strtolower(end(explode('.', $colour)));
                        $html .= '<li>';
                        $html .= '<a href="#" onclick="changeInputColor(\'' . $colour . '\', \'' . $type . '\', this, \'' . $wrapper . '-content\'); return false"';
                        if ($color != NULL && $color == $colour) {
                            $html .= ' class="active"';
                        }
                        $html .= '>';
                         $html .= '<img src="' . Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . 'frontend/'.$themepack.'/default/asset/colour/' . $file . '" alt=""/>';
                        $html .= '</a>';
                        $html .= '</li>';
                    }
                }
                $html .= '</ul>';
            }
        }
        return $html;
    }

    // get child block class
    public function getChildClass($_block, $setting) {
        $class = 'col-md-' . $_block->getCol();

        if (isset($setting['custom_class']) && $setting['custom_class'] != '') {
            $class .= ' ' . $setting['custom_class'];
        }
        if (isset($setting['text_colour']) && $setting['text_colour'] != '') {
            $class .= ' ' . $setting['text_colour'];
        }
        if (isset($setting['link_colour']) && $setting['link_colour'] != '') {
            $class .= ' ' . $setting['link_colour'];
        }
        if (isset($setting['link_hover_colour']) && $setting['link_hover_colour'] != '') {
            $class .= ' ' . $setting['link_hover_colour'];
        }
        if (isset($setting['button_colour']) && $setting['button_colour'] != '') {
            $class .= ' ' . $setting['button_colour'];
        }
        if (isset($setting['button_hover_colour']) && $setting['button_hover_colour'] != '') {
            $class .= ' ' . $setting['button_hover_colour'];
        }
        if (isset($setting['button_text_colour']) && $setting['button_text_colour'] != '') {
            $class .= ' ' . $setting['button_text_colour'];
        }
        if (isset($setting['button_text_hover_colour']) && $setting['button_text_hover_colour'] != '') {
            $class .= ' ' . $setting['button_text_hover_colour'];
        }
        if (isset($setting['button_border_colour']) && $setting['button_border_colour'] != '') {
            $class .= ' ' . $setting['button_border_colour'];
        }
        if (isset($setting['button_border_hover_colour']) && $setting['button_border_hover_colour'] != '') {
            $class .= ' ' . $setting['button_border_hover_colour'];
        }
        if (isset($setting['price_colour']) && $setting['price_colour'] != '') {
            $class .= ' ' . $setting['price_colour'];
        }

        return $class;
    }

    // get category setting (ratio, product per row, description position)
    public function getCatSetting($catId) {
        $setting = Mage::getModel('mpanel/setting')->load($catId);
        if ($setting) {
            return $setting->getData();
        }
        return false;
    }

    public function getProductTabs() {
        $tabs = array();
        if (Mage::getStoreConfig('mpanel/product_tabs/show_description')) {
            if (Mage::getStoreConfig('mpanel/product_tabs/position_description')) {
                $position = (int) Mage::getStoreConfig('mpanel/product_tabs/position_description');
            } else {
                $position = 0;
            }
            $tabs['description'] = $position;
        }
        if (Mage::getStoreConfig('mpanel/product_tabs/show_additional')) {
            if (Mage::getStoreConfig('mpanel/product_tabs/position_additional')) {
                $position = (int) Mage::getStoreConfig('mpanel/product_tabs/position_additional');
            } else {
                $position = 0;
            }
            $tabs['additional'] = $position;
        }
        if (Mage::getStoreConfig('mpanel/product_tabs/show_reviews')) {
            if (Mage::getStoreConfig('mpanel/product_tabs/position_reviews')) {
                $position = (int) Mage::getStoreConfig('mpanel/product_tabs/position_reviews');
            } else {
                $position = 0;
            }
            $tabs['reviews'] = $position;
        }
        if (Mage::getStoreConfig('mpanel/product_tabs/show_product_tag_list')) {
            if (Mage::getStoreConfig('mpanel/product_tabs/position_product_tag_list')) {
                $position = (int) Mage::getStoreConfig('mpanel/product_tabs/position_product_tag_list');
            } else {
                $position = 0;
            }
            $tabs['product_tag_list'] = $position;
        }
        if ((Mage::getStoreConfig('mpanel/product_tabs/show_custom_tab_one') && Mage::getStoreConfig('mpanel/product_tabs/custom_tab_one') != '' && Mage::getStoreConfig('mpanel/product_tabs/custom_tab_one_title') != '' && Mage::getStoreConfig('mpanel/product_tabs/custom_tab_one_identifier_value') != '') || (Mage::getStoreConfig('mpanel/product_tabs/show_custom_tab_one') && Mage::getStoreConfig('mpanel/product_tabs/custom_tab_one') != '' && Mage::getStoreConfig('mpanel/product_tabs/custom_tab_one_title') != '' && Mage::getStoreConfig('mpanel/product_tabs/custom_tab_one_attribute_code_value') != '')) {
            if (Mage::getStoreConfig('mpanel/product_tabs/position_custom_tab_one')) {
                $position = (int) Mage::getStoreConfig('mpanel/product_tabs/position_custom_tab_one');
            } else {
                $position = 0;
            }
            $tabs['custom_tab_one'] = $position;
        }
        if ((Mage::getStoreConfig('mpanel/product_tabs/show_custom_tab_two') && Mage::getStoreConfig('mpanel/product_tabs/custom_tab_two') != '' && Mage::getStoreConfig('mpanel/product_tabs/custom_tab_two_title') != '' && Mage::getStoreConfig('mpanel/product_tabs/custom_tab_two_identifier_value') != '') || (Mage::getStoreConfig('mpanel/product_tabs/show_custom_tab_two') && Mage::getStoreConfig('mpanel/product_tabs/custom_tab_two') != '' && Mage::getStoreConfig('mpanel/product_tabs/custom_tab_two_title') != '' && Mage::getStoreConfig('mpanel/product_tabs/custom_tab_two_attribute_code_value') != '')) {
            if (Mage::getStoreConfig('mpanel/product_tabs/position_custom_tab_two')) {
                $position = (int) Mage::getStoreConfig('mpanel/product_tabs/position_custom_tab_two');
            } else {
                $position = 0;
            }
            $tabs['custom_tab_two'] = $position;
        }
        if ((Mage::getStoreConfig('mpanel/product_tabs/show_custom_tab_three') && Mage::getStoreConfig('mpanel/product_tabs/custom_tab_three') != '' && Mage::getStoreConfig('mpanel/product_tabs/custom_tab_three_title') != '' && Mage::getStoreConfig('mpanel/product_tabs/custom_tab_three_identifier_value') != '') || (Mage::getStoreConfig('mpanel/product_tabs/show_custom_tab_three') && Mage::getStoreConfig('mpanel/product_tabs/custom_tab_three') != '' && Mage::getStoreConfig('mpanel/product_tabs/custom_tab_three_title') != '' && Mage::getStoreConfig('mpanel/product_tabs/custom_tab_three_attribute_code_value') != '')) {
            if (Mage::getStoreConfig('mpanel/product_tabs/position_custom_tab_three')) {
                $position = (int) Mage::getStoreConfig('mpanel/product_tabs/position_custom_tab_three');
            } else {
                $position = 0;
            }
            $tabs['custom_tab_three'] = $position;
        }
        if (Mage::helper('productquestions')->isActive()) {
            if (Mage::getStoreConfig('mpanel/product_tabs/show_product_questions')) {
                if (Mage::getStoreConfig('mpanel/product_tabs/position_product_questions')) {
                    $position = (int) Mage::getStoreConfig('mpanel/product_tabs/position_product_questions');
                } else {
                    $position = 0;
                }
                $tabs['product_questions'] = $position;
            }
        }
        asort($tabs);
        return $tabs;
    }

    // Check use custom color for header or not
    public function useHeaderCustomColor() {
        return Mage::getStoreConfig('theme_color/header/use_custom');
    }

    // get header custom color
    public function getHeaderColorSetting() {
        $setting = array(
            /* Header Top Style */
            '.header-top, #header-v2 .header-top' => array(
                'background-color' => Mage::getStoreConfig('theme_color/header/top_background'),
                'color' => Mage::getStoreConfig('theme_color/header/top_text_color'),
            ),
            '.header-top a, #header-v2 .header-top a' => array(
                'color' => Mage::getStoreConfig('theme_color/header/top_link_color')
            ),
            '.header-top a:hover, #header-v2 .header-top a:hover' => array(
                'color' => Mage::getStoreConfig('theme_color/header/top_link_hover_color')
            ),
            '.header-top .dropdown-menu, #header-v2 .header-top .dropdown-menu' => array(
                'background-color' => Mage::getStoreConfig('theme_color/header/top_dropdown_background'),
                'border-color' => Mage::getStoreConfig('theme_color/header/top_dropdown_border')
            ),
            '.header-top .dropdown-menu a, #header-v2 .header-top .dropdown-menu a' => array(
                'color' => Mage::getStoreConfig('theme_color/header/top_dropdown_link')
            ),
            '.header-top .dropdown-menu a:hover, #header-v2 .header-top .dropdown-menu a:hover' => array(
                'color' => Mage::getStoreConfig('theme_color/header/top_dropdown_link_hover')
            ),
            /* Header Middle Background */
            'header .header-content' => array(
                'background-color' => Mage::getStoreConfig('theme_color/header/middle_background')
            ),
            /* Search Box Style */
            'header .search-mini-form .form-search' => array(
                'background-color' => Mage::getStoreConfig('theme_color/header/search_background')
            ),
            'header .search' => array(
                'color' => Mage::getStoreConfig('theme_color/header/search_text_color')
            ),
            'header .search a' => array(
                'color' => Mage::getStoreConfig('theme_color/header/search_link_color')
            ),
            'header .search a:hover' => array(
                'color' => Mage::getStoreConfig('theme_color/header/search_link_hover_color')
            ),
            'header .search-mini-form .input-text' => array(
                'color' => Mage::getStoreConfig('theme_color/header/search_input_color')
            ),
            'header .search .button .fa,.search-form .action-search [class*="pe-"], .account-dropdown .dropdown-toggle [class*="pe-"], .block-cart-header .trigger-open-cart [class*="pe-"], .wishlist-link a [class*="pe-"]' => array(
                'color' => Mage::getStoreConfig('theme_color/header/search_icon_color')
            ),
            'header .search .cd-dropdown ul span' => array(
                'background-color' => Mage::getStoreConfig('theme_color/header/search_dropdown_background'),
                'color' => Mage::getStoreConfig('theme_color/header/search_dropdown_text_color')
            ),
            'header .search .cd-dropdown ul span:hover' => array(
                'background-color' => Mage::getStoreConfig('theme_color/header/search_dropdown_hover_background'),
                'color' => Mage::getStoreConfig('theme_color/header/search_dropdown_text_hover_color')
            ),
            /* Header Top Cart Style */
            'header .block-cart-header' => array(
                'color' => Mage::getStoreConfig('theme_color/header/cart_text_color')
            ),
            'header .block-cart-header .price' => array(
                'color' => Mage::getStoreConfig('theme_color/header/cart_price_color')
            ),
            'header .block-cart-header .img-cart' => array(
                'background-color' => Mage::getStoreConfig('theme_color/header/cart_icon_background')
            ),
            'header .block-cart-header .summary strong' => array(
                'background-color' => Mage::getStoreConfig('theme_color/header/cart_number_background'),
                'color' => Mage::getStoreConfig('theme_color/header/cart_number_text')
            ),
            /* Main Menu Style */
            '#nav_container' => array(
                'background-color' => Mage::getStoreConfig('theme_color/header/menu_background')
            ),
            '#nav_container #mainMenu a.level0' => array(
                'color' => Mage::getStoreConfig('theme_color/header/menu_link_color')
            ),
            '#nav_container #mainMenu a.level0:hover' => array(
                'color' => Mage::getStoreConfig('theme_color/header/menu_link_hover_color')
            ),
            '#nav_container .sub-container' => array(
                'border-color' => Mage::getStoreConfig('theme_color/header/menu_border_color')
            ),
        );
        $setting = array_filter($setting);
        return $setting;
    }

    // Check use custom color for footer or not
    public function useFooterCustomColor() {
        return Mage::getStoreConfig('theme_color/footer/use_custom');
    }

    // Check use custom color for main content or not
    public function useMainCustomColor() {
        return Mage::getStoreConfig('theme_color/main/use_custom');
    }

    // get header custom color
    public function getFooterColorSetting() {
        $setting = array(
            // Top Footer
            '.top-footer' => array(
                'background-color' => Mage::getStoreConfig('theme_color/footer/top_background'),
                'color' => Mage::getStoreConfig('theme_color/footer/top_text_color'),
                'border-color' => Mage::getStoreConfig('theme_color/footer/top_border_color')
            ),
            '.top-footer a' => array(
                'color' => Mage::getStoreConfig('theme_color/footer/top_link_color')
            ),
            '.top-footer a:hover' => array(
                'color' => Mage::getStoreConfig('theme_color/footer/top_link_hover_color')
            ),
            '.top-footer h2,.top-footer h3,.top-footer h4,.top-footer h5,.top-footer h6' => array(
                'color' => Mage::getStoreConfig('theme_color/footer/top_heading_color')
            ),
            '.top-footer .fa' => array(
                'color' => Mage::getStoreConfig('theme_color/footer/top_icon_color')
            ),
            // Middle Footer
            '.middle-footer' => array(
                'background-color' => Mage::getStoreConfig('theme_color/footer/middle_background'),
                'color' => Mage::getStoreConfig('theme_color/footer/middle_text_color'),
                'border-color' => Mage::getStoreConfig('theme_color/footer/middle_border_color')
            ),
            '.middle-footer a' => array(
                'color' => Mage::getStoreConfig('theme_color/footer/middle_link_color')
            ),
            '.middle-footer a:hover' => array(
                'color' => Mage::getStoreConfig('theme_color/footer/middle_link_hover_color')
            ),
            '.middle-footer h2,.middle-footer h3,.middle-footer h4,.middle-footer h5,.middle-footer h6' => array(
                'color' => Mage::getStoreConfig('theme_color/footer/middle_heading_color')
            ),
            '.middle-footer .fa' => array(
                'color' => Mage::getStoreConfig('theme_color/footer/middle_icon_color')
            ),
            // Bottom Footer
            '.bottom-footer' => array(
                'background-color' => Mage::getStoreConfig('theme_color/footer/bottom_background'),
                'color' => Mage::getStoreConfig('theme_color/footer/bottom_text_color'),
                'border-color' => Mage::getStoreConfig('theme_color/footer/bottom_border_color')
            ),
            '.bottom-footer a' => array(
                'color' => Mage::getStoreConfig('theme_color/footer/bottom_link_color')
            ),
            '.bottom-footer a:hover' => array(
                'color' => Mage::getStoreConfig('theme_color/footer/bottom_link_hover_color')
            ),
            '.bottom-footer h2,.bottom-footer h3,.bottom-footer h4,.bottom-footer h5,.bottom-footer h6' => array(
                'color' => Mage::getStoreConfig('theme_color/footer/bottom_heading_color')
            ),
            '.bottom-footer .fa' => array(
                'color' => Mage::getStoreConfig('theme_color/footer/bottom_icon_color')
            )
        );
        $setting = array_filter($setting);
        return $setting;
    }

    // get main custom color
    public function getMainColorSetting() {
        $setting = array(
            'a' => array(
                'color' => Mage::getStoreConfig('theme_color/main/link_color'),
            ),
            'a:hover' => array(
                'color' => Mage::getStoreConfig('theme_color/main/link_color')
            ),
            '.regular-price .price, .special-price .price, .price' => array(
                'color' => Mage::getStoreConfig('theme_color/main/price_color')
            ),
            'body' => array(
                'color' => Mage::getStoreConfig('theme_color/main/color')
            ),
            '.btn-default' => array(
                'color' => Mage::getStoreConfig('theme_color/main/button_color'),
                'background' => Mage::getStoreConfig('theme_color/main/button_background'),
                'border-color' => Mage::getStoreConfig('theme_color/main/button_border_color')
            ),
            '.btn-default:hover' => array(
                'color' => Mage::getStoreConfig('theme_color/main/button_hover_color'),
                'background' => Mage::getStoreConfig('theme_color/main/button_hover_background'),
                'border-color' => Mage::getStoreConfig('theme_color/main/button_border_hover_color')
            ),
            '.btn-primary' => array(
                'color' => Mage::getStoreConfig('theme_color/main/button_one_color'),
                'background' => Mage::getStoreConfig('theme_color/main/button_one_background'),
                'border-color' => Mage::getStoreConfig('theme_color/main/button_one_border')
            ),
            '.btn-primary:hover' => array(
                'color' => Mage::getStoreConfig('theme_color/main/button_one_hover_color'),
                'background' => Mage::getStoreConfig('theme_color/main/button_one_hover_background'),
                'border-color' => Mage::getStoreConfig('theme_color/main/button_one_border_hover')
            ),
            '.btn-secondary' => array(
                'color' => Mage::getStoreConfig('theme_color/main/button_two_color'),
                'background' => Mage::getStoreConfig('theme_color/main/button_two_background'),
                'border-color' => Mage::getStoreConfig('theme_color/main/button_two_border')
            ),
            '.btn-secondary:hover' => array(
                'color' => Mage::getStoreConfig('theme_color/main/button_two_hover_color'),
                'background' => Mage::getStoreConfig('theme_color/main/button_two_hover_background'),
                'border-color' => Mage::getStoreConfig('theme_color/main/button_two_border_hover')
            )
        );
        $setting = array_filter($setting);
        return $setting;
    }

    public function getFrontendHeaderColorSettings() {
        $setting = array(
            'top_header' => array(
                'heading' => $this->__('Top Link Section'),
                'top_background' => array('label' => $this->__('Background color'), 'value' => Mage::getStoreConfig('theme_color/header/top_background')),
                'top_text_color' => array('label' => $this->__('Text color'), 'value' => Mage::getStoreConfig('theme_color/header/top_text_color')),
                'top_link_color' => array('label' => $this->__('Link color'), 'value' => Mage::getStoreConfig('theme_color/header/top_link_color')),
                'top_link_hover_color' => array('label' => $this->__('Link hover color'), 'value' => Mage::getStoreConfig('theme_color/header/top_link_hover_color')),
                'top_dropdown_background' => array('label' => $this->__('Dropdown background color'), 'value' => Mage::getStoreConfig('theme_color/header/top_dropdown_background')),
                'top_dropdown_link' => array('label' => $this->__('Dropdown link color'), 'value' => Mage::getStoreConfig('theme_color/header/top_dropdown_link')),
                'top_dropdown_link_hover' => array('label' => $this->__('Dropdown link hover color'), 'value' => Mage::getStoreConfig('theme_color/header/top_dropdown_link_hover')),
                'top_dropdown_border' => array('label' => $this->__('Dropdown border color'), 'value' => Mage::getStoreConfig('theme_color/header/top_dropdown_border')),
            ),
            'middle_header' => array(
                'heading' => $this->__('Header Middle Content Section'),
                'middle_background' => array('label' => $this->__('Background color'), 'value' => Mage::getStoreConfig('theme_color/header/middle_background'))
            ),
            'top_search' => array(
                'heading' => $this->__('Top Search Section'),
                'search_background' => array('label' => $this->__('Background color'), 'value' => Mage::getStoreConfig('theme_color/header/search_background')),
                'search_text_color' => array('label' => $this->__('Text color'), 'value' => Mage::getStoreConfig('theme_color/header/search_text_color')),
                'search_link_color' => array('label' => $this->__('Link color'), 'value' => Mage::getStoreConfig('theme_color/header/search_link_color')),
                'search_link_hover_color' => array('label' => $this->__('Link hover color'), 'value' => Mage::getStoreConfig('theme_color/header/search_link_hover_color')),
                'search_input_color' => array('label' => $this->__('Input text color'), 'value' => Mage::getStoreConfig('theme_color/header/search_input_color')),
                'search_icon_color' => array('label' => $this->__('Search icon color'), 'value' => Mage::getStoreConfig('theme_color/header/search_icon_color')),
                'search_dropdown_background' => array('label' => $this->__('Dropdown background color'), 'value' => Mage::getStoreConfig('theme_color/header/search_dropdown_background')),
                'search_dropdown_hover_background' => array('label' => $this->__('Dropdown hover background color'), 'value' => Mage::getStoreConfig('theme_color/header/search_dropdown_hover_background')),
                'search_dropdown_text_color' => array('label' => $this->__('Dropdown text color'), 'value' => Mage::getStoreConfig('theme_color/header/search_dropdown_text_color')),
                'search_dropdown_text_hover_color' => array('label' => $this->__('Dropdown text hover color'), 'value' => Mage::getStoreConfig('theme_color/header/search_dropdown_text_hover_color'))
            ),
            'top_cart' => array(
                'heading' => $this->__('Top Cart Section'),
                'cart_text_color' => array('label' => $this->__('Text color'), 'value' => Mage::getStoreConfig('theme_color/header/cart_text_color')),
                'cart_price_color' => array('label' => $this->__('Price color'), 'value' => Mage::getStoreConfig('theme_color/header/cart_price_color')),
                'cart_icon_background' => array('label' => $this->__('Icon background color'), 'value' => Mage::getStoreConfig('theme_color/header/cart_icon_background')),
                'cart_number_background' => array('label' => $this->__('Number background color'), 'value' => Mage::getStoreConfig('theme_color/header/cart_number_background')),
                'cart_number_text' => array('label' => $this->__('Number text color'), 'value' => Mage::getStoreConfig('theme_color/header/cart_number_text'))
            ),
            'top_menu' => array(
                'heading' => $this->__('Menu Section'),
                'menu_background' => array('label' => $this->__('Background color'), 'value' => Mage::getStoreConfig('theme_color/header/menu_background')),
                'menu_link_color' => array('label' => $this->__('Link color'), 'value' => Mage::getStoreConfig('theme_color/header/menu_link_color')),
                'menu_link_hover_color' => array('label' => $this->__('Link hover color'), 'value' => Mage::getStoreConfig('theme_color/header/menu_link_hover_color')),
                'menu_border_color' => array('label' => $this->__('Border color'), 'value' => Mage::getStoreConfig('theme_color/header/menu_border_color'))
            )
        );
        return $setting;
    }

    public function getFrontendFooterColorSettings() {
        $setting = array(
            'top_footer' => array(
                'heading' => $this->__('Top Footer Section'),
                'top_background' => array('label' => $this->__('Background color'), 'value' => Mage::getStoreConfig('theme_color/footer/top_background')),
                'top_text_color' => array('label' => $this->__('Text color'), 'value' => Mage::getStoreConfig('theme_color/footer/top_text_color')),
                'top_heading_color' => array('label' => $this->__('Heading color'), 'value' => Mage::getStoreConfig('theme_color/footer/top_heading_color')),
                'top_link_color' => array('label' => $this->__('Link color'), 'value' => Mage::getStoreConfig('theme_color/footer/top_link_color')),
                'top_link_hover_color' => array('label' => $this->__('Link hover color'), 'value' => Mage::getStoreConfig('theme_color/footer/top_link_hover_color')),
                'top_icon_color' => array('label' => $this->__('Icon color'), 'value' => Mage::getStoreConfig('theme_color/footer/top_icon_color')),
                'top_border_color' => array('label' => $this->__('Border color'), 'value' => Mage::getStoreConfig('theme_color/footer/top_border_color'))
            ),
            'middle_footer' => array(
                'heading' => $this->__('Middle Footer Section'),
                'middle_background' => array('label' => $this->__('Background color'), 'value' => Mage::getStoreConfig('theme_color/footer/middle_background')),
                'middle_text_color' => array('label' => $this->__('Text color'), 'value' => Mage::getStoreConfig('theme_color/footer/middle_text_color')),
                'middle_heading_color' => array('label' => $this->__('Heading color'), 'value' => Mage::getStoreConfig('theme_color/footer/middle_heading_color')),
                'middle_link_color' => array('label' => $this->__('Link color'), 'value' => Mage::getStoreConfig('theme_color/footer/middle_link_color')),
                'middle_link_hover_color' => array('label' => $this->__('Link hover color'), 'value' => Mage::getStoreConfig('theme_color/footer/middle_link_hover_color')),
                'middle_icon_color' => array('label' => $this->__('Icon color'), 'value' => Mage::getStoreConfig('theme_color/footer/middle_icon_color')),
                'middle_border_color' => array('label' => $this->__('Border color'), 'value' => Mage::getStoreConfig('theme_color/footer/middle_border_color')),
            ),
            'bottom_footer' => array(
                'heading' => $this->__('Bottom Footer Section'),
                'bottom_background' => array('label' => $this->__('Background color'), 'value' => Mage::getStoreConfig('theme_color/footer/bottom_background')),
                'bottom_text_color' => array('label' => $this->__('Text color'), 'value' => Mage::getStoreConfig('theme_color/footer/bottom_text_color')),
                'bottom_heading_color' => array('label' => $this->__('Heading color'), 'value' => Mage::getStoreConfig('theme_color/footer/bottom_heading_color')),
                'bottom_link_color' => array('label' => $this->__('Link color'), 'value' => Mage::getStoreConfig('theme_color/footer/bottom_link_color')),
                'bottom_link_hover_color' => array('label' => $this->__('Link hover color'), 'value' => Mage::getStoreConfig('theme_color/footer/bottom_link_hover_color')),
                'bottom_icon_color' => array('label' => $this->__('Icon color'), 'value' => Mage::getStoreConfig('theme_color/footer/bottom_icon_color')),
                'bottom_border_color' => array('label' => $this->__('Border color'), 'value' => Mage::getStoreConfig('theme_color/footer/bottom_border_color')),
            )
        );
        return $setting;
    }

    public function getFrontendMainColorSettings() {

        $setting = array(
            'text_link' => array(
                'default_color' => array('label' => $this->__('Default Color'), 'value' => Mage::getStoreConfig('theme_color/main/default_color')),
                'heading' => $this->__('Text & Link'),
                'color' => array('label' => $this->__('Text color'), 'value' => Mage::getStoreConfig('theme_color/main/color')),
                'price_color' => array('label' => $this->__('Price color'), 'value' => Mage::getStoreConfig('theme_color/main/price_color')),
                'link_color' => array('label' => $this->__('Link color'), 'value' => Mage::getStoreConfig('theme_color/main/link_color')),
                'link_hover_color' => array('label' => $this->__('Link hover color'), 'value' => Mage::getStoreConfig('theme_color/main/link_hover_color'))
            ),
            'default_button' => array(
                'heading' => $this->__('Default Button'),
                'button_color' => array('label' => $this->__('Button color'), 'value' => Mage::getStoreConfig('theme_color/main/button_color')),
                'button_hover_color' => array('label' => $this->__('Button hover color'), 'value' => Mage::getStoreConfig('theme_color/main/button_hover_color')),
                'button_background' => array('label' => $this->__('Button background'), 'value' => Mage::getStoreConfig('theme_color/main/button_background')),
                'button_hover_background' => array('label' => $this->__('Button hover background'), 'value' => Mage::getStoreConfig('theme_color/main/button_hover_background')),
                'button_border_color' => array('label' => $this->__('Button border color'), 'value' => Mage::getStoreConfig('theme_color/main/button_border_color')),
                'button_border_hover_color' => array('label' => $this->__('Button hover border color'), 'value' => Mage::getStoreConfig('theme_color/main/button_border_hover_color'))
            ),
            'primary_button' => array(
                'heading' => $this->__('Primary Button'),
                'button_one_color' => array('label' => $this->__('Button color'), 'value' => Mage::getStoreConfig('theme_color/main/button_one_color')),
                'button_one_hover_color' => array('label' => $this->__('Button hover color'), 'value' => Mage::getStoreConfig('theme_color/main/button_one_hover_color')),
                'button_one_background' => array('label' => $this->__('Button background'), 'value' => Mage::getStoreConfig('theme_color/main/button_one_background')),
                'button_one_hover_background' => array('label' => $this->__('Button hover background'), 'value' => Mage::getStoreConfig('theme_color/main/button_one_hover_background')),
                'button_one_border' => array('label' => $this->__('Button border color'), 'value' => Mage::getStoreConfig('theme_color/main/button_one_border')),
                'button_one_border_hover' => array('label' => $this->__('Button hover border color'), 'value' => Mage::getStoreConfig('theme_color/main/button_one_border_hover'))
            ),
            'secondary_button' => array(
                'heading' => $this->__('Secondary Button'),
                'button_two_color' => array('label' => $this->__('Button color'), 'value' => Mage::getStoreConfig('theme_color/main/button_two_color')),
                'button_two_hover_color' => array('label' => $this->__('Button hover color'), 'value' => Mage::getStoreConfig('theme_color/main/button_two_hover_color')),
                'button_two_background' => array('label' => $this->__('Button background'), 'value' => Mage::getStoreConfig('theme_color/main/button_two_background')),
                'button_two_hover_background' => array('label' => $this->__('Button hover background'), 'value' => Mage::getStoreConfig('theme_color/main/button_two_hover_background')),
                'button_two_border' => array('label' => $this->__('Button border color'), 'value' => Mage::getStoreConfig('theme_color/main/button_two_border')),
                'button_two_border_hover' => array('label' => $this->__('Button hover border color'), 'value' => Mage::getStoreConfig('theme_color/main/button_two_border_hover'))
            )
        );
        return $setting;
    }

    public function getPerrow() {
        $perrow = Mage::getStoreConfig('mpanel/catalog/product_per_row');

        if ($this->isCategoryPage()) {
            $catId = Mage::app()->getRequest()->getParam('id');
            $setting = Mage::getModel('mpanel/setting')->load($catId);
            if ($setting) {
                if ($setting->getNumberProductOnRow() != '') {
                    $perrow = $setting->getNumberProductOnRow();
                }
            }
        }

        if (Mage::app()->getRequest()->getModuleName() == 'catalogsearch') {
            if (Mage::getStoreConfig('mpanel/catalogsearch/product_per_row') != '') {
                $perrow = Mage::getStoreConfig('mpanel/catalogsearch/product_per_row');
            }
        }
        return $perrow;
    }

    public function getItemClass($perrow, $row) {
        $class = '';
        if ($perrow > 2 && $row > 2) {
            for ($i = 2; $i <= $perrow; $i++) {
                if (($row - 1) % $i == 0) {
                    $class.=' row-' . $i . '-first';
                }
            }
        }
        return $class;
    }

    public function getOtherHomepage() {
        $storeId = Mage::app()->getStore()->getId();
        $collection = Mage::getModel('mpanel/childs')->getCollection()->addFieldToSelect('store_id')->addFieldToFilter('store_id', array('neq' => $storeId));
        $collection->getSelect()->group('store_id');
        return $collection;
    }

    public function getStoreView($storeId) {
        $store = Mage::getModel('core/store')->load($storeId);

        return $store->getName() . ' (' . $store->getFrontendName() . ')';
    }

    public function checkCategoryPage() {
        if (Mage::app()->getFrontController()->getAction()->getFullActionName() == 'catalog_category_view') {
            return true;
        }
        return false;
    }

    public function checkProductPage() {
        if (Mage::app()->getFrontController()->getAction()->getFullActionName() == 'catalog_product_view') {
            return true;
        }
        return false;
    }

    public function checkSearchPage() {
        if (Mage::app()->getRequest()->getModuleName() == 'catalogsearch') {
            return true;
        }
        return false;
    }

    public function checkCmsPage() {
        if (Mage::app()->getRequest()->getModuleName() == 'cms') {
            return true;
        }
        return false;
    }

    public function checkAccountPage() {
        if (Mage::app()->getRequest()->getModuleName() == 'customer') {
            return true;
        }
        return false;
    }

    public function checkBrandPage() {
        if (Mage::app()->getRequest()->getModuleName() == 'brand') {
            return true;
        }
        return false;
    }

    public function checkBlogPage() {
        if (Mage::app()->getRequest()->getModuleName() == 'blog') {
            return true;
        }
        return false;
    }

    public function getCategoryLeft() {
        if ($this->acceptToUsePanel()) {
            return $this->getLayout()->createBlock('mpanel/layout_category_left')->setTemplate('mgs/mpanel/template/admin/layout/category/left.phtml')->toHtml();
        } else {
            return $this->getLayout()->createBlock('mpanel/layout_category_left')->setTemplate('mgs/mpanel/template/layout/category/left.phtml')->toHtml();
        }
    }

    public function getCategoryRight() {
        if ($this->acceptToUsePanel()) {
            return $this->getLayout()->createBlock('mpanel/layout_category_right')->setTemplate('mgs/mpanel/template/admin/layout/category/right.phtml')->toHtml();
        } else {
            return $this->getLayout()->createBlock('mpanel/layout_category_right')->setTemplate('mgs/mpanel/template/layout/category/right.phtml')->toHtml();
        }
    }

    public function getProductLeft() {
        if ($this->acceptToUsePanel()) {
            return $this->getLayout()->createBlock('mpanel/layout_product_left')->setTemplate('mgs/mpanel/template/admin/layout/product/left.phtml')->toHtml();
        } else {
            return $this->getLayout()->createBlock('mpanel/layout_product_left')->setTemplate('mgs/mpanel/template/layout/product/left.phtml')->toHtml();
        }
    }

    public function getProductRight() {
        if ($this->acceptToUsePanel()) {
            return $this->getLayout()->createBlock('mpanel/layout_product_right')->setTemplate('mgs/mpanel/template/admin/layout/product/right.phtml')->toHtml();
        } else {
            return $this->getLayout()->createBlock('mpanel/layout_product_right')->setTemplate('mgs/mpanel/template/layout/product/right.phtml')->toHtml();
        }
    }

    public function getSearchLeft() {
        if ($this->acceptToUsePanel()) {
            return $this->getLayout()->createBlock('mpanel/layout_search_left')->setTemplate('mgs/mpanel/template/admin/layout/search/left.phtml')->toHtml();
        } else {
            return $this->getLayout()->createBlock('mpanel/layout_search_left')->setTemplate('mgs/mpanel/template/layout/search/left.phtml')->toHtml();
        }
    }

    public function getSearchRight() {
        if ($this->acceptToUsePanel()) {
            return $this->getLayout()->createBlock('mpanel/layout_search_right')->setTemplate('mgs/mpanel/template/admin/layout/search/right.phtml')->toHtml();
        } else {
            return $this->getLayout()->createBlock('mpanel/layout_search_right')->setTemplate('mgs/mpanel/template/layout/search/right.phtml')->toHtml();
        }
    }

    public function getPageLeft() {
        if ($this->acceptToUsePanel()) {
            return $this->getLayout()->createBlock('mpanel/layout_page_left')->setTemplate('mgs/mpanel/template/admin/layout/page/left.phtml')->toHtml();
        } else {
            return $this->getLayout()->createBlock('mpanel/layout_page_left')->setTemplate('mgs/mpanel/template/layout/page/left.phtml')->toHtml();
        }
    }

    public function getPageRight() {
        if ($this->acceptToUsePanel()) {
            return $this->getLayout()->createBlock('mpanel/layout_page_right')->setTemplate('mgs/mpanel/template/admin/layout/page/right.phtml')->toHtml();
        } else {
            return $this->getLayout()->createBlock('mpanel/layout_page_right')->setTemplate('mgs/mpanel/template/layout/page/right.phtml')->toHtml();
        }
    }

    public function getAccountLeft() {
        if ($this->acceptToUsePanel()) {
            return $this->getLayout()->createBlock('mpanel/layout_account_left')->setTemplate('mgs/mpanel/template/admin/layout/account/left.phtml')->toHtml();
        } else {
            return $this->getLayout()->createBlock('mpanel/layout_account_left')->setTemplate('mgs/mpanel/template/layout/account/left.phtml')->toHtml();
        }
    }

    public function getAccountRight() {
        if ($this->acceptToUsePanel()) {
            return $this->getLayout()->createBlock('mpanel/layout_account_right')->setTemplate('mgs/mpanel/template/admin/layout/account/right.phtml')->toHtml();
        } else {
            return $this->getLayout()->createBlock('mpanel/layout_account_right')->setTemplate('mgs/mpanel/template/layout/account/right.phtml')->toHtml();
        }
    }

    public function getBrandLeft() {
        if ($this->acceptToUsePanel()) {
            return $this->getLayout()->createBlock('mpanel/layout_brand_left')->setTemplate('mgs/mpanel/template/admin/layout/brand/left.phtml')->toHtml();
        } else {
            return $this->getLayout()->createBlock('mpanel/layout_brand_left')->setTemplate('mgs/mpanel/template/layout/brand/left.phtml')->toHtml();
        }
    }

    public function getBrandRight() {
        if ($this->acceptToUsePanel()) {
            return $this->getLayout()->createBlock('mpanel/layout_brand_right')->setTemplate('mgs/mpanel/template/admin/layout/brand/right.phtml')->toHtml();
        } else {
            return $this->getLayout()->createBlock('mpanel/layout_brand_right')->setTemplate('mgs/mpanel/template/layout/brand/right.phtml')->toHtml();
        }
    }

    public function getBlogLeft() {
        if ($this->acceptToUsePanel()) {
            return $this->getLayout()->createBlock('mpanel/layout_blog_left')->setTemplate('mgs/mpanel/template/admin/layout/blog/left.phtml')->toHtml();
        } else {
            return $this->getLayout()->createBlock('mpanel/layout_blog_left')->setTemplate('mgs/mpanel/template/layout/blog/left.phtml')->toHtml();
        }
    }

    public function getBlogRight() {
        if ($this->acceptToUsePanel()) {
            return $this->getLayout()->createBlock('mpanel/layout_blog_right')->setTemplate('mgs/mpanel/template/admin/layout/blog/right.phtml')->toHtml();
        } else {
            return $this->getLayout()->createBlock('mpanel/layout_blog_right')->setTemplate('mgs/mpanel/template/layout/blog/right.phtml')->toHtml();
        }
    }

    public function renderButtons($blockId, $controllerName) {
        $model = Mage::getModel('mpanel/block')->load($blockId);
        $coreBlocks = array('sub_categories', 'layered_navigation', 'cart_sidebar', 'compare_sidebar', 'reorder_sidebar', 'product_viewed', 'product_related', 'wishlist_sidebar', 'tags_popular', 'newsletter');
        $productBlocks = array('featured_products', 'bestseller_products', 'new_products', 'top_rate_products', 'sale_products', '', '', '', '');
        if (in_array($model->getType(), $coreBlocks)) {
            $actionName = 'editCoreBlock';
        }
        if (in_array($model->getType(), $productBlocks)) {
            $actionName = 'editProductBlock';
        }
        if ($model->getType() == 'static_block') {
            $actionName = 'editStaticBlock';
        }
        if ($model->getType() == 'category_navigation') {
            $actionName = 'editCategoryNavigationBlock';
        }
        if ($model->getType() == 'poll') {
            $actionName = 'editPollBlock';
        }
        if ($model->getType() == 'promo_banner') {
            $actionName = 'editPromoBlock';
        }
        if ($model->getType() == 'menu') {
            $actionName = 'editMenuBlock';
        }
        if ($model->getType() == 'facebook_like_box') {
            $actionName = 'editFacebookLikeBoxBlock';
        }
        if ($model->getType() == 'twitter_feed') {
            $actionName = 'editTwitterFeedBlock';
        }
        $html = '<div class="edit-panel child-panel"><ul>';
        $html .= '<li><a href="' . Mage::getUrl('mpanel/' . $controllerName . '/' . $actionName, array('block_id' => $blockId, 'page_type' => $model->getPageType(), 'page_id' => $model->getPageId(), 'type' => $model->getType(), 'place' => $model->getPlace())) . '" class="popup-link" title="' . $this->__('Edit') . '"><em class="fa fa-edit">&nbsp;</em></a></li>';
        $html .= '<li class="sort-handle"><a href="#" onclick="return false;" title="' . $this->__('Move') . '"><em class="fa fa-arrows">&nbsp;</em></a></li>';
        $html .= '<li><a href="' . Mage::getUrl('mpanel/' . $controllerName . '/delete', array('block_id' => $blockId)) . '" onclick="return confirm(\'' . $this->__('Are you sure you would like to remove this block?') . '\')" title="' . $this->__('Delete') . '"><em class="fa fa-trash">&nbsp;</em></a></li>';
        $html .= '</ul></div>';
        return $html;
    }

    public function renderContent($blockId) {
        $html = '';
        $model = Mage::getModel('mpanel/block')->load($blockId);
        $options = unserialize($model->getOptions());
        if ($model->getType() == 'static_block') {
            $html .= $this->getLayout()
                    ->createBlock('cms/block')
                    ->setTitle($options['title'])
                    ->setBlockId($options['block_id'])
                    ->toHtml();
        }
        if ($model->getType() == 'category_navigation') {
            $html .= $this->getLayout()
                    ->createBlock('mpanel/navigation')
                    ->setTitle($options['title'])
                    ->setSelectedCategoryId($options['selected_category_id'])
                    ->setTemplate('mgs/mpanel/template/category-navigation.phtml')
                    ->toHtml();
        }
        if ($model->getType() == 'sub_categories') {
            $html .= $this->getLayout()
                    ->createBlock('catalog/navigation')
                    ->setTitle($options['title'])
                    ->setTemplate('catalog/navigation/left.phtml')
                    ->toHtml();
        }
        if ($model->getType() == 'layered_navigation') {
            if ($this->checkBrandPage()) {
                if (Mage::app()->getFrontController()->getAction()->getFullActionName() == 'brand_index_view') {
                    $html .= $this->getLayout()
                            ->createBlock('brand/layer_view')
                            ->setTitle($options['title'])
                            ->setTemplate('mgs/brand/layer/view.phtml')
                            ->toHtml();
                }
            } else {
				if($model->getPageType()=='search'){
					$html .= $this->getLayout()->getBlock('catalogsearch.leftnav')->toHtml();
				}else{
					$html .= $this->getLayout()->getBlock('catalog.leftnav')->toHtml();
				}
                
            }
        }
        if ($model->getType() == 'cart_sidebar') {
            $html .= $this->getLayout()
                    ->createBlock('checkout/cart_sidebar')
                    ->setTitle($options['title'])
                    ->setTemplate('checkout/cart/sidebar.phtml')
                    ->toHtml();
        }
        if ($model->getType() == 'compare_sidebar') {
            $html .= $this->getLayout()
                    ->createBlock('catalog/product_compare_sidebar')
                    ->setTitle($options['title'])
                    ->setTemplate('catalog/product/compare/sidebar.phtml')
                    ->toHtml();
        }
        if ($model->getType() == 'reorder_sidebar') {
            $html .= $this->getLayout()
                    ->createBlock('sales/reorder_sidebar')
                    ->setTitle($options['title'])
                    ->setTemplate('sales/reorder/sidebar.phtml')
                    ->toHtml();
        }
        if ($model->getType() == 'poll') {
            $html .= $this->getLayout()
                    ->createBlock('poll/activePoll')
                    ->setTitle($options['title'])
                    ->setPollId($options['poll_id'])
                    ->setPollTemplate('poll/active.phtml', 'poll')
                    ->setPollTemplate('poll/result.phtml', 'results')
                    ->toHtml();
        }
        if ($model->getType() == 'product_related') {
            $html .= $this->getLayout()
                    ->createBlock('catalog/product_list_related')
                    ->setTitle($options['title'])
                    ->setTemplate('catalog/product/list/related.phtml')
                    ->toHtml();
        }
        if ($model->getType() == 'product_viewed') {
            $html .= $this->getLayout()
                    ->createBlock('reports/product_viewed')
                    ->setTitle($options['title'])
                    ->setTemplate('reports/product_viewed.phtml')
                    ->toHtml();
        }
        if ($model->getType() == 'wishlist_sidebar') {
            $html .= $this->getLayout()
                    ->createBlock('wishlist/customer_sidebar')
                    ->setTitle($options['title'])
                    ->setTemplate('wishlist/sidebar.phtml')
                    ->toHtml();
        }
        if ($model->getType() == 'tags_popular') {
            $html .= $this->getLayout()
                    ->createBlock('tag/popular')
                    ->setTitle($options['title'])
                    ->setTemplate('tag/popular.phtml')
                    ->toHtml();
        }
        if ($model->getType() == 'newsletter') {
            $html .= $this->getLayout()
                    ->createBlock('newsletter/subscribe')
                    ->setTitle($options['title'])
                    ->setTemplate('newsletter/subscribe.phtml')
                    ->toHtml();
        }
        if ($model->getType() == 'promo_banner') {
            $html .= $this->getLayout()
                    ->createBlock('promobanners/promobanners')
                    ->setTitle($options['title'])
                    ->setBannerId($options['banner_id'])
                    ->setTemplate('mgs/promobanners/banner.phtml')
                    ->toHtml();
        }
        if ($model->getType() == 'menu') {
            $html .= $this->getLayout()
                    ->createBlock('megamenu/vertical')
                    ->setTitle($options['title'])
                    ->setMenuId($options['menu_id'])
                    ->setTemplate('megamenu/vertical.phtml')
                    ->toHtml();
        }
        if ($model->getType() == 'featured_products') {
            $html .= $this->getLayout()
                    ->createBlock('mpanel/products')
                    ->setTitle($options['title'])
                    ->setProductsCount($options['products_count'])
                    ->setTemplate('mgs/mpanel/products/list/featured_products.phtml')
                    ->toHtml();
        }
        if ($model->getType() == 'bestseller_products') {
            $html .= $this->getLayout()
                    ->createBlock('mpanel/products')
                    ->setTitle($options['title'])
                    ->setProductsCount($options['products_count'])
                    ->setTemplate('mgs/mpanel/products/list/hot_products.phtml')
                    ->toHtml();
        }
        if ($model->getType() == 'new_products') {
            $html .= $this->getLayout()
                    ->createBlock('mpanel/product_new')
                    ->setTitle($options['title'])
                    ->setProductsCount($options['products_count'])
                    ->setTemplate('mgs/mpanel/products/list/new_products.phtml')
                    ->toHtml();
        }
        if ($model->getType() == 'top_rate_products') {
            $html .= $this->getLayout()
                    ->createBlock('mpanel/product_rate')
                    ->setTitle($options['title'])
                    ->setProductsCount($options['products_count'])
                    ->setTemplate('mgs/mpanel/products/list/rate_products.phtml')
                    ->toHtml();
        }
        if ($model->getType() == 'sale_products') {
            $html .= $this->getLayout()
                    ->createBlock('mpanel/product_sale')
                    ->setTitle($options['title'])
                    ->setProductsCount($options['products_count'])
                    ->setTemplate('mgs/mpanel/products/list/sale_products.phtml')
                    ->toHtml();
        }
        if ($model->getType() == 'facebook_like_box') {
            $html .= $this->getLayout()
                    ->createBlock('social/fblikebox')
                    ->setTitle($options['title'])
                    ->setPageId($options['page_id'])
                    ->setWidth($options['width'])
                    ->setHeight($options['height'])
                    ->setUseSmallHeader($options['use_small_header'])
                    ->setDataAdaptContainerWidth($options['data_adapt_container_width'])
                    ->setDataHideCover($options['data_hide_cover'])
                    ->setDataShowFacepile($options['data_show_facepile'])
                    ->setDataShowPosts($options['data_show_posts'])
                    ->setTemplate('mgs/social/facebook-like-box.phtml')
                    ->toHtml();
        }
        if ($model->getType() == 'twitter_feed') {
            $html .= $this->getLayout()
                    ->createBlock('core/template')
                    ->setTitle($options['title'])
                    ->setUser($options['user'])
                    ->setCount($options['count'])
                    ->setTruncate($options['truncate'])
                    ->setTemplate('mgs/mpanel/twitter_tweets.phtml')
                    ->toHtml();
        }
        return $html;
    }

    public function enabledButtonForLeftRight() {
        $template = $this->getLayout()->getBlock('root')->getTemplate();
        if ($template == 'page/3columns.phtml' || $template == 'page/2columns-left.phtml' || $template == 'page/2columns-right.phtml') {
            return true;
        } else {
            return false;
        }
    }

    public function getLinksFont() {
        $setting = $this->getThemeSettings();
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

        if (!in_array($setting['custom_font'], $fonts)) {
            $fonts[] = $setting['custom_font'];
        }

        $fonts = array_filter($fonts);
        $links = '';
        $isSecure = Mage::app()->getStore()->isCurrentlySecure();
        foreach ($fonts as $_font) {
            if ($isSecure) {
                $links .= '<link href="https://fonts.googleapis.com/css?family=' . $_font . ':300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,900,900italic" rel="stylesheet" type="text/css"/>';
            } else {
                $links .= '<link href="//fonts.googleapis.com/css?family=' . $_font . ':300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,900,900italic" rel="stylesheet" type="text/css"/>';
            }
        }

        return $links;
    }
	
	public function getProductLayout($product){
		$pageLayout = $product->getPageLayout();
		
		if($pageLayout==''){
			$pageLayout = Mage::getStoreConfig('mpanel/catalog/product_layout');
		}
		
		if($pageLayout==''){
			$pageLayout = 'one_column';
		}
		
		return $pageLayout;
	}

}
