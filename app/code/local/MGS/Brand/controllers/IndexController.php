<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

class MGS_Brand_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        $this->loadLayout();
        if (Mage::helper('brand')->title() != '') {
            $this->getLayout()->getBlock('head')->setTitle($this->__(Mage::helper('brand')->title()));
        } else {
            $this->getLayout()->getBlock('head')->setTitle($this->__('Brand'));
        }
        if (Mage::helper('brand')->metaKeywords() != '') {
            $this->getLayout()->getBlock('head')->setKeywords($this->__(Mage::helper('brand')->metaKeywords()));
        }
        if (Mage::helper('brand')->metaDescription() != '') {
            $this->getLayout()->getBlock('head')->setDescription($this->__(Mage::helper('brand')->metaDescription()));
        }
        if (Mage::helper('brand')->pageTemplateList() != '') {
            $this->getLayout()->getBlock('root')->setTemplate('page/' . Mage::helper('brand')->pageTemplateList() . '.phtml');
        }
        if (Mage::helper('brand')->showBreadcrumbs()) {
            if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
                if (Mage::helper('brand')->title() != '') {
                    $text = Mage::helper('brand')->title();
                } else {
                    $text = $this->__('Brand');
                }
                $breadcrumbs->addCrumb('home', array(
                    'label' => $this->__('Home'),
                    'title' => $this->__('Go to Home Page'),
                    'link' => Mage::getBaseUrl()
                ))->addCrumb('brand', array(
                    'label' => $text,
                    'title' => $text
                ));
            }
        }
        $this->renderLayout();
    }

    public function viewAction() {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('brand/brand')->load($id);
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__($model->getTitle()));
        $this->getLayout()->getBlock('head')->setKeywords($this->__($model->getMetaKeywords()));
        $this->getLayout()->getBlock('head')->setDescription($this->__($model->getMetaDescription()));
        if (Mage::helper('brand')->pageTemplateView() != '') {
            $this->getLayout()->getBlock('root')->setTemplate('page/' . Mage::helper('brand')->pageTemplateView() . '.phtml');
        }
        if (Mage::helper('brand')->layeredNavigationView() != '' || Mage::helper('brand')->layeredNavigationView() != 'no') {
            if (Mage::helper('brand')->layeredNavigationView() == 'top-left') {
                $leftBlock = $this->getLayout()->createBlock('brand/layer_view', 'brand.nav')->setTemplate('mgs/brand/layer/view.phtml');
                $stateRenderersBlock = $this->getLayout()->createBlock('core/text_list', 'brand.nav.state.renderers');
                $leftBlock->setChild('state_renderers', $stateRenderersBlock);
                $this->getLayout()->getBlock('left')->insert($leftBlock, '-', false);
            } else if (Mage::helper('brand')->layeredNavigationView() == 'bottom-left') {
                $leftBlock = $this->getLayout()->createBlock('brand/layer_view', 'brand.nav')->setTemplate('mgs/brand/layer/view.phtml');
                $stateRenderersBlock = $this->getLayout()->createBlock('core/text_list', 'brand.nav.state.renderers');
                $leftBlock->setChild('state_renderers', $stateRenderersBlock);
                $this->getLayout()->getBlock('left')->insert($leftBlock, '-', true);
            } else if (Mage::helper('brand')->layeredNavigationView() == 'top-right') {
                $rightBlock = $this->getLayout()->createBlock('brand/layer_view', 'brand.nav')->setTemplate('mgs/brand/layer/view.phtml');
                $stateRenderersBlock = $this->getLayout()->createBlock('core/text_list', 'brand.nav.state.renderers');
                $rightBlock->setChild('state_renderers', $stateRenderersBlock);
                $this->getLayout()->getBlock('right')->insert($rightBlock, '-', false);
            } else if (Mage::helper('brand')->layeredNavigationView() == 'bottom-right') {
                $rightBlock = $this->getLayout()->createBlock('brand/layer_view', 'brand.nav')->setTemplate('mgs/brand/layer/view.phtml');
                $stateRenderersBlock = $this->getLayout()->createBlock('core/text_list', 'brand.nav.state.renderers');
                $rightBlock->setChild('state_renderers', $stateRenderersBlock);
                $this->getLayout()->getBlock('right')->insert($rightBlock, '-', true);
            } else if (Mage::helper('brand')->layeredNavigationView() == 'top-content') {
                $block = $this->getLayout()->createBlock('brand/layer_view', 'brand.nav')->setTemplate('mgs/brand/layer/view.phtml');
                $stateRenderersBlock = $this->getLayout()->createBlock('core/text_list', 'brand.nav.state.renderers');
                $block->setChild('state_renderers', $stateRenderersBlock);
                $this->getLayout()->getBlock('content')->insert($block, '-', false);
            } else if (Mage::helper('brand')->layeredNavigationView() == 'bottom-content') {
                $block = $this->getLayout()->createBlock('brand/layer_view', 'brand.nav')->setTemplate('mgs/brand/layer/view.phtml');
                $stateRenderersBlock = $this->getLayout()->createBlock('core/text_list', 'brand.nav.state.renderers');
                $block->setChild('state_renderers', $stateRenderersBlock);
                $this->getLayout()->getBlock('content')->insert($block, '-', true);
            }
        }
        if (Mage::helper('brand')->showBreadcrumbs()) {
            if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
                if (Mage::helper('brand')->title() != '') {
                    $text = Mage::helper('brand')->title();
                } else {
                    $text = $this->__('Brand');
                }
                if (Mage::helper('brand')->urlKey() != '') {
                    $url = Mage::getBaseUrl() . Mage::helper('brand')->urlKey();
                } else {
                    $url = Mage::getBaseUrl() . 'brand';
                }
                $breadcrumbs->addCrumb('home', array(
                            'label' => $this->__('Home'),
                            'title' => $this->__('Go to Home Page'),
                            'link' => Mage::getBaseUrl()
                        ))->addCrumb('brand', array(
                            'label' => $text,
                            'title' => $text,
                            'link' => $url
                        ))
                        ->addCrumb('manufacturer', array(
                            'label' => $model->getTitle(),
                            'title' => $model->getTitle()
                ));
            }
        }
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('checkout/session');
        $this->renderLayout();
    }

}
