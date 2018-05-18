<?php

class MGS_Mpanel_ProductController extends Mage_Core_Controller_Front_Action {

    protected function _checkAccept() {
        if (!Mage::helper('mpanel')->acceptToUsePanel()) {
            $this->_redirectUrl(Mage::getUrl());
            return;
        }
    }

    public function addAction() {
        $this->_checkAccept();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function addStaticBlockAction() {
        $this->_checkAccept();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function addCategoryNavigationBlockAction() {
        $this->_checkAccept();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function addCoreBlockAction() {
        $this->_checkAccept();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function addPollBlockAction() {
        $this->_checkAccept();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function addPromoBlockAction() {
        $this->_checkAccept();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function addMenuBlockAction() {
        $this->_checkAccept();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function addProductBlockAction() {
        $this->_checkAccept();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function addFacebookLikeBoxBlockAction() {
        $this->_checkAccept();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function addTwitterFeedBlockAction() {
        $this->_checkAccept();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function editStaticBlockAction() {
        $this->_checkAccept();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function editCategoryNavigationBlockAction() {
        $this->_checkAccept();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function editCoreBlockAction() {
        $this->_checkAccept();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function editPollBlockAction() {
        $this->_checkAccept();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function editPromoBlockAction() {
        $this->_checkAccept();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function editMenuBlockAction() {
        $this->_checkAccept();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function editProductBlockAction() {
        $this->_checkAccept();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function editFacebookLikeBoxBlockAction() {
        $this->_checkAccept();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function editTwitterFeedBlockAction() {
        $this->_checkAccept();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function saveCoreBlockAction() {
        $this->_checkAccept();
        $response = array();
        if ($data = $this->getRequest()->getPost()) {
            try {
                $data['options'] = serialize(array('title' => $data['title']));
                $collection = Mage::getModel('mpanel/block')->getCollection()
                        ->addFieldToFilter('page_type', array('eq' => $data['page_type']))
                        ->addFieldToFilter('page_id', array('eq' => $data['page_id']))
                        ->addFieldToFilter('place', array('eq' => $data['place']))
                        ->addFieldToFilter('type', array('eq' => $data['type']));
                if (count($collection)) {
                    $response['status'] = 'error';
                    $response['message'] = $this->__('This block already exists.');
                } else {
                    $this->_copy($data['page_id']);
                    $model = Mage::getModel('mpanel/block');
                    $model->setData($data);
                    $model->save();
                    $response['status'] = 'success';
                    $response['message'] = $this->__('This block was successfully saved.');
                }
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = $this->__($e->getMessage());
                Mage::logException($e->getMessage());
            }
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }

    public function updateCoreBlockAction() {
        $this->_checkAccept();
        $response = array();
        if ($data = $this->getRequest()->getPost()) {
            try {
                $blockId = $data['block_id'];
                unset($data['block_id']);
                $data['options'] = serialize(array('title' => $data['title']));
                $model = Mage::getModel('mpanel/block');
                $model->setData($data)
                        ->setId($blockId);
                $model->save();
                $response['status'] = 'success';
                $response['message'] = $this->__('This block was successfully updated.');
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = $this->__($e->getMessage());
                Mage::logException($e->getMessage());
            }
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }

    public function saveProductBlockAction() {
        $this->_checkAccept();
        $response = array();
        if ($data = $this->getRequest()->getPost()) {
            try {
                $data['options'] = serialize(array('title' => $data['title'], 'products_count' => $data['products_count']));
                $collection = Mage::getModel('mpanel/block')->getCollection()
                        ->addFieldToFilter('page_type', array('eq' => $data['page_type']))
                        ->addFieldToFilter('page_id', array('eq' => $data['page_id']))
                        ->addFieldToFilter('place', array('eq' => $data['place']))
                        ->addFieldToFilter('type', array('eq' => $data['type']));
                if (count($collection)) {
                    $response['status'] = 'error';
                    $response['message'] = $this->__('This block already exists.');
                } else {
                    $this->_copy($data['page_id']);
                    $model = Mage::getModel('mpanel/block');
                    $model->setData($data);
                    $model->save();
                    $response['status'] = 'success';
                    $response['message'] = $this->__('This block was successfully saved.');
                }
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = $this->__($e->getMessage());
                Mage::logException($e->getMessage());
            }
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }

    public function updateProductBlockAction() {
        $this->_checkAccept();
        $response = array();
        if ($data = $this->getRequest()->getPost()) {
            try {
                $blockId = $data['block_id'];
                unset($data['block_id']);
                $data['options'] = serialize(array('title' => $data['title'], 'products_count' => $data['products_count']));
                $model = Mage::getModel('mpanel/block');
                $model->setData($data)
                        ->setId($blockId);
                $model->save();
                $response['status'] = 'success';
                $response['message'] = $this->__('This block was successfully updated.');
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = $this->__($e->getMessage());
                Mage::logException($e->getMessage());
            }
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }

    public function saveStaticBlockAction() {
        $this->_checkAccept();
        $response = array();
        if ($data = $this->getRequest()->getPost()) {
            try {
                if (isset($data['block_type']) && $data['block_type'] == 'new') {
                    $staticBlock = array(
                        'title' => $data['title'],
                        'identifier' => $data['identifier'],
                        'content' => $data['content'],
                        'is_active' => 1,
                        'stores' => array(Mage::app()->getStore()->getStoreId())
                    );
                    $collection = Mage::getModel('cms/block')->getCollection()
                            ->addFieldToFilter('identifier', array('eq' => $data['identifier']))
                            ->addStoreFilter(Mage::app()->getStore()->getStoreId(), false);
                    if (count($collection)) {
                        $response['status'] = 'error';
                        $response['message'] = $this->__('A block identifier with the same properties already exists in the selected store.');
                    } else {
                        $static = Mage::getModel('cms/block')->setData($staticBlock)->save();
                        $data['options'] = serialize(array('title' => $static->getTitle(), 'block_id' => $static->getId()));
                        $this->_copy($data['page_id']);
                        $model = Mage::getModel('mpanel/block');
                        $model->setData($data);
                        $model->save();
                        $response['status'] = 'success';
                        $response['message'] = $this->__('This block was successfully saved.');
                    }
                }
                if (isset($data['block_type']) && $data['block_type'] == 'exist') {
                    $static = Mage::getModel('cms/block')->load($data['static_block_id']);
                    $data['options'] = serialize(array('title' => $static->getTitle(), 'block_id' => $static->getId()));
                    $this->_copy($data['page_id']);
                    $model = Mage::getModel('mpanel/block');
                    $model->setData($data);
                    $model->save();
                    $response['status'] = 'success';
                    $response['message'] = $this->__('This block was successfully saved.');
                }
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = $this->__($e->getMessage());
                Mage::logException($e->getMessage());
            }
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }

    public function updateStaticBlockAction() {
        $this->_checkAccept();
        $response = array();
        if ($data = $this->getRequest()->getPost()) {
            try {
                $blockId = $data['block_id'];
                unset($data['block_id']);
                $staticBlock = array(
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'is_active' => 1,
                    'stores' => array(Mage::app()->getStore()->getStoreId())
                );
                $staticBlockId = Mage::getModel('cms/block')->getCollection()
                        ->addFieldToFilter('identifier', array('eq' => $data['identifier']))
                        ->addStoreFilter(Mage::app()->getStore()->getStoreId(), false)
                        ->getFirstItem()
                        ->getId();
                $static = Mage::getModel('cms/block')->setData($staticBlock)->setId($staticBlockId)->save();
                $data['options'] = serialize(array('title' => $static->getTitle(), 'block_id' => $static->getId()));
                $model = Mage::getModel('mpanel/block');
                $model->setData($data)
                        ->setId($blockId);
                $model->save();
                $response['status'] = 'success';
                $response['message'] = $this->__('This block was successfully updated.');
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = $this->__($e->getMessage());
                Mage::logException($e->getMessage());
            }
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }

    public function saveCategoryNavigationBlockAction() {
        $this->_checkAccept();
        $response = array();
        if ($data = $this->getRequest()->getPost()) {
            try {
                $data['options'] = serialize(array('title' => $data['title'], 'selected_category_id' => $data['selected_category_id']));
                $collection = Mage::getModel('mpanel/block')->getCollection()
                        ->addFieldToFilter('page_type', array('eq' => $data['page_type']))
                        ->addFieldToFilter('page_id', array('eq' => $data['page_id']))
                        ->addFieldToFilter('place', array('eq' => $data['place']))
                        ->addFieldToFilter('type', array('eq' => $data['type']));
                if (count($collection)) {
                    $response['status'] = 'error';
                    $response['message'] = $this->__('This block already exists.');
                } else {
                    $this->_copy($data['page_id']);
                    $model = Mage::getModel('mpanel/block');
                    $model->setData($data);
                    $model->save();
                    $response['status'] = 'success';
                    $response['message'] = $this->__('This block was successfully saved.');
                }
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = $this->__($e->getMessage());
                Mage::logException($e->getMessage());
            }
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }

    public function updateCategoryNavigationBlockAction() {
        $this->_checkAccept();
        $response = array();
        if ($data = $this->getRequest()->getPost()) {
            try {
                $blockId = $data['block_id'];
                unset($data['block_id']);
                $data['options'] = serialize(array('title' => $data['title'], 'selected_category_id' => $data['selected_category_id']));
                $model = Mage::getModel('mpanel/block');
                $model->setData($data)
                        ->setId($blockId);
                $model->save();
                $response['status'] = 'success';
                $response['message'] = $this->__('This block was successfully updated.');
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = $this->__($e->getMessage());
                Mage::logException($e->getMessage());
            }
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }

    public function savePromoBlockAction() {
        $this->_checkAccept();
        $response = array();
        if ($data = $this->getRequest()->getPost()) {
            try {
                if ($data['chooser'] == 'new') {
                    if (isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
                        try {
                            $uploader = new Varien_File_Uploader('filename');
                            $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png', 'JPG', 'JPEG', 'GIF', 'PNG'));
                            $uploader->setAllowRenameFiles(false);
                            $uploader->setFilesDispersion(false);
                            $path = Mage::getBaseDir('media') . DS . 'promobanners' . DS;
                            $uploader->save($path, $_FILES['filename']['name']);
                        } catch (Exception $e) {
                            
                        }
                        $data['filename'] = $_FILES['filename']['name'];
                    }
                    $model = Mage::getModel('promobanners/promobanners');
                    $model->setTitle($data['title']);
                    $model->setFilename($data['filename']);
                    $model->setStatus(1);
                    $model->setButton($data['button']);
                    $model->setUrl($data['url']);
                    $model->setContent($data['content']);
                    $model->save();
                    $promoId = $model->getId();
                } else {
                    $promoId = $data['exist_id'];
                }
                $blockId = $data['block_id'];
                unset($data['block_id']);
                $banner = Mage::getModel('promobanners/promobanners')->load($promoId);
                $data['title'] = $banner->getTitle();
                $data['options'] = serialize(array('title' => $banner->getTitle(), 'banner_id' => $banner->getId()));
                $this->_copy($data['page_id']);
                $model = Mage::getModel('mpanel/block');
                $model->setData($data)
                        ->setId($blockId);
                $model->save();
                $response['status'] = 'success';
                $response['message'] = $this->__('This block was successfully updated.');
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = $this->__($e->getMessage());
                Mage::logException($e->getMessage());
            }
        }
        $data = $this->getRequest()->getPost();
        $oRewrite = Mage::getModel('core/url_rewrite')
                ->setStoreId(Mage::app()->getStore()->getStoreId())
                ->getCollection()
                ->addFieldToFilter('category_id', array('eq' => $data['page_id']))
                ->addFieldToFilter('product_id', array('eq' => $data['product_id']))
                ->getFirstItem();
        Mage::getSingleton('core/session')->setUrlRedirect(Mage::getBaseUrl() . $oRewrite->getData('request_path'));
        $this->_redirect('mpanel/index/after');
        return;
    }

    public function updatePromoBlockAction() {
        $this->_checkAccept();
        $response = array();
        if ($data = $this->getRequest()->getPost()) {
            try {
                if ($data['chooser'] == 'new') {
                    if (isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
                        try {
                            $uploader = new Varien_File_Uploader('filename');
                            $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png', 'JPG', 'JPEG', 'GIF', 'PNG'));
                            $uploader->setAllowRenameFiles(false);
                            $uploader->setFilesDispersion(false);
                            $path = Mage::getBaseDir('media') . DS . 'promobanners' . DS;
                            $uploader->save($path, $_FILES['filename']['name']);
                        } catch (Exception $e) {
                            
                        }
                        $data['filename'] = $_FILES['filename']['name'];
                    }
                    $model = Mage::getModel('promobanners/promobanners');
                    $model->setTitle($data['title']);
                    $model->setFilename($data['filename']);
                    $model->setStatus(1);
                    $model->setButton($data['button']);
                    $model->setUrl($data['url']);
                    $model->setContent($data['content']);
                    $model->save();
                    $promoId = $model->getId();
                } else {
                    $promoId = $data['exist_id'];
                }
                $banner = Mage::getModel('promobanners/promobanners')->load($promoId);
                $data['title'] = $banner->getTitle();
                $data['options'] = serialize(array('title' => $banner->getTitle(), 'banner_id' => $banner->getId()));
                $model = Mage::getModel('mpanel/block');
                $model->setData($data);
                $model->save();
                $response['status'] = 'success';
                $response['message'] = $this->__('This block was successfully saved.');
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = $this->__($e->getMessage());
                Mage::logException($e->getMessage());
            }
        }
        $data = $this->getRequest()->getPost();
        $oRewrite = Mage::getModel('core/url_rewrite')
                ->setStoreId(Mage::app()->getStore()->getStoreId())
                ->getCollection()
                ->addFieldToFilter('category_id', array('eq' => $data['page_id']))
                ->getFirstItem();
        Mage::getSingleton('core/session')->setUrlRedirect(Mage::getBaseUrl() . $oRewrite->getData('request_path'));
        $this->_redirect('mpanel/index/after');
        return;
    }

    public function savePollBlockAction() {
        $this->_checkAccept();
        $response = array();
        if ($data = $this->getRequest()->getPost()) {
            try {
                $data['options'] = serialize(array('title' => $data['title'], 'poll_id' => $data['poll_id']));
                $collection = Mage::getModel('mpanel/block')->getCollection()
                        ->addFieldToFilter('page_type', array('eq' => $data['page_type']))
                        ->addFieldToFilter('page_id', array('eq' => $data['page_id']))
                        ->addFieldToFilter('place', array('eq' => $data['place']))
                        ->addFieldToFilter('type', array('eq' => $data['type']));
                if (count($collection)) {
                    $response['status'] = 'error';
                    $response['message'] = $this->__('This block already exists.');
                } else {
                    $this->_copy($data['page_id']);
                    $model = Mage::getModel('mpanel/block');
                    $model->setData($data);
                    $model->save();
                    $response['status'] = 'success';
                    $response['message'] = $this->__('This block was successfully saved.');
                }
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = $this->__($e->getMessage());
                Mage::logException($e->getMessage());
            }
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }

    public function updatePollBlockAction() {
        $this->_checkAccept();
        $response = array();
        if ($data = $this->getRequest()->getPost()) {
            try {
                $blockId = $data['block_id'];
                unset($data['block_id']);
                $data['options'] = serialize(array('title' => $data['title'], 'poll_id' => $data['poll_id']));
                $model = Mage::getModel('mpanel/block');
                $model->setData($data)
                        ->setId($blockId);
                $model->save();
                $response['status'] = 'success';
                $response['message'] = $this->__('This block was successfully updated.');
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = $this->__($e->getMessage());
                Mage::logException($e->getMessage());
            }
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }

    public function saveMenuBlockAction() {
        $this->_checkAccept();
        $response = array();
        if ($data = $this->getRequest()->getPost()) {
            try {
                $data['options'] = serialize(array('title' => $data['title'], 'menu_id' => $data['menu_id']));
                $collection = Mage::getModel('mpanel/block')->getCollection()
                        ->addFieldToFilter('page_type', array('eq' => $data['page_type']))
                        ->addFieldToFilter('page_id', array('eq' => $data['page_id']))
                        ->addFieldToFilter('place', array('eq' => $data['place']))
                        ->addFieldToFilter('type', array('eq' => $data['type']));
                if (count($collection)) {
                    $response['status'] = 'error';
                    $response['message'] = $this->__('This block already exists.');
                } else {
                    $this->_copy($data['page_id']);
                    $model = Mage::getModel('mpanel/block');
                    $model->setData($data);
                    $model->save();
                    $response['status'] = 'success';
                    $response['message'] = $this->__('This block was successfully saved.');
                }
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = $this->__($e->getMessage());
                Mage::logException($e->getMessage());
            }
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }

    public function updateMenuBlockAction() {
        $this->_checkAccept();
        $response = array();
        if ($data = $this->getRequest()->getPost()) {
            try {
                $blockId = $data['block_id'];
                unset($data['block_id']);
                $data['options'] = serialize(array('title' => $data['title'], 'menu_id' => $data['menu_id']));
                $model = Mage::getModel('mpanel/block');
                $model->setData($data)
                        ->setId($blockId);
                $model->save();
                $response['status'] = 'success';
                $response['message'] = $this->__('This block was successfully updated.');
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = $this->__($e->getMessage());
                Mage::logException($e->getMessage());
            }
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }

    public function saveFacebookLikeBoxBlockAction() {
        $this->_checkAccept();
        $response = array();
        if ($data = $this->getRequest()->getPost()) {
            try {
                $data['options'] = serialize(array(
                    'title' => $data['title'],
                    'page_id' => $data['facebook_page_id'],
                    'width' => $data['width'],
                    'height' => $data['height'],
                    'use_small_header' => $data['use_small_header'],
                    'data_adapt_container_width' => $data['data_adapt_container_width'],
                    'data_hide_cover' => $data['data_hide_cover'],
                    'data_show_facepile' => $data['data_show_facepile'],
                    'data_show_posts' => $data['data_show_posts'])
                );
                $collection = Mage::getModel('mpanel/block')->getCollection()
                        ->addFieldToFilter('page_type', array('eq' => $data['page_type']))
                        ->addFieldToFilter('page_id', array('eq' => $data['page_id']))
                        ->addFieldToFilter('place', array('eq' => $data['place']))
                        ->addFieldToFilter('type', array('eq' => $data['type']));
                if (count($collection)) {
                    $response['status'] = 'error';
                    $response['message'] = $this->__('This block already exists.');
                } else {
                    $this->_copy($data['page_id']);
                    $model = Mage::getModel('mpanel/block');
                    $model->setData($data);
                    $model->save();
                    $response['status'] = 'success';
                    $response['message'] = $this->__('This block was successfully saved.');
                }
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = $this->__($e->getMessage());
                Mage::logException($e->getMessage());
            }
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }

    public function updateFacebookLikeBoxBlockAction() {
        $this->_checkAccept();
        $response = array();
        if ($data = $this->getRequest()->getPost()) {
            try {
                $blockId = $data['block_id'];
                unset($data['block_id']);
                $data['options'] = serialize(array(
                    'title' => $data['title'],
                    'page_id' => $data['facebook_page_id'],
                    'width' => $data['width'],
                    'height' => $data['height'],
                    'use_small_header' => $data['use_small_header'],
                    'data_adapt_container_width' => $data['data_adapt_container_width'],
                    'data_hide_cover' => $data['data_hide_cover'],
                    'data_show_facepile' => $data['data_show_facepile'],
                    'data_show_posts' => $data['data_show_posts'])
                );
                $model = Mage::getModel('mpanel/block');
                $model->setData($data)
                        ->setId($blockId);
                $model->save();
                $response['status'] = 'success';
                $response['message'] = $this->__('This block was successfully updated.');
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = $this->__($e->getMessage());
                Mage::logException($e->getMessage());
            }
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }

    public function saveTwitterFeedBlockAction() {
        $this->_checkAccept();
        $response = array();
        if ($data = $this->getRequest()->getPost()) {
            try {
                $data['options'] = serialize(array('title' => $data['title'], 'user' => $data['user'], 'count' => $data['count'], 'truncate' => $data['truncate']));
                $collection = Mage::getModel('mpanel/block')->getCollection()
                        ->addFieldToFilter('page_type', array('eq' => $data['page_type']))
                        ->addFieldToFilter('page_id', array('eq' => $data['page_id']))
                        ->addFieldToFilter('place', array('eq' => $data['place']))
                        ->addFieldToFilter('type', array('eq' => $data['type']));
                if (count($collection)) {
                    $response['status'] = 'error';
                    $response['message'] = $this->__('This block already exists.');
                } else {
                    $this->_copy($data['page_id']);
                    $model = Mage::getModel('mpanel/block');
                    $model->setData($data);
                    $model->save();
                    $response['status'] = 'success';
                    $response['message'] = $this->__('This block was successfully saved.');
                }
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = $this->__($e->getMessage());
                Mage::logException($e->getMessage());
            }
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }

    public function updateTwitterFeedBlockAction() {
        $this->_checkAccept();
        $response = array();
        if ($data = $this->getRequest()->getPost()) {
            try {
                $blockId = $data['block_id'];
                unset($data['block_id']);
                $data['options'] = serialize(array('title' => $data['title'], 'user' => $data['user'], 'count' => $data['count'], 'truncate' => $data['truncate']));
                $model = Mage::getModel('mpanel/block');
                $model->setData($data)
                        ->setId($blockId);
                $model->save();
                $response['status'] = 'success';
                $response['message'] = $this->__('This block was successfully updated.');
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = $this->__($e->getMessage());
                Mage::logException($e->getMessage());
            }
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }

    public function sortAction() {
        $this->_checkAccept();
        $response = array();
        if (($data = $this->getRequest()->getPost()) && ($element = $this->getRequest()->getParam('el'))) {
            if (isset($data[$element]) && count($data[$element]) > 0) {
                foreach ($data[$element] as $key => $value) {
                    try {
                        $model = Mage::getModel('mpanel/block')->load($value);
                        $model->setSortOrder($key);
                        $model->save();
                        $response[$value] = $this->__('Sorted.');
                    } catch (Exception $e) {
                        $response[$value] = $this->__($e->getMessage());
                        Mage::logException($e->getMessage());
                    }
                }
            }
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }

    public function deleteAction() {
        $this->_checkAccept();
        if ($data = $this->getRequest()->getParams()) {
            try {
                $model = Mage::getModel('mpanel/block')->load($data['block_id']);
                if ($model->getType() == 'static_block') {
                    try {
                        $options = unserialize($model->getOptions());
                        $static = Mage::getModel('cms/block')->load($options['block_id']);
                        $static->delete();
                    } catch (Exception $e) {
                        Mage::logException($e->getMessage());
                    }
                }
                $model->delete();
            } catch (Exception $e) {
                Mage::logException($e->getMessage());
            }
        }
        $this->_redirectReferer();
        return;
    }

    public function applyToAllAction() {
        $this->_checkAccept();
        if ($data = $this->getRequest()->getParams()) {
            try {
                $pageId = $data['page_id'];
                $deleteCollection = Mage::getModel('mpanel/block')
                        ->getCollection()
                        ->addFieldToFilter('page_type', array('eq' => 'product'))
                        ->addFieldToFilter('page_id', array('eq' => 0))
                        ->setOrder('sort_order', 'ASC');
                foreach ($deleteCollection as $block) {
                    $model = Mage::getModel('mpanel/block')->load($block->getId());
                    $model->delete();
                }
                $collection = Mage::getModel('mpanel/block')
                        ->getCollection()
                        ->addFieldToFilter('page_type', array('eq' => 'product'))
                        ->addFieldToFilter('page_id', array('eq' => $pageId))
                        ->setOrder('sort_order', 'ASC');
                foreach ($collection as $block) {
                    $model = Mage::getModel('mpanel/block')->load($block->getId());
                    $model->setPageId(0);
                    $model->save();
                }
            } catch (Exception $ex) {
                Mage::logException($e->getMessage());
            }
        }
        $this->_redirectReferer();
        return;
    }

    public function resetLayoutAction() {
        $this->_checkAccept();
        if ($data = $this->getRequest()->getParams()) {
            try {
                $pageId = $data['page_id'];
                $collection = Mage::getModel('mpanel/block')
                        ->getCollection()
                        ->addFieldToFilter('page_type', array('eq' => 'product'))
                        ->addFieldToFilter('page_id', array('eq' => $pageId))
                        ->setOrder('sort_order', 'ASC');
                foreach ($collection as $block) {
                    $model = Mage::getModel('mpanel/block')->load($block->getId());
                    $model->delete();
                }
            } catch (Exception $ex) {
                Mage::logException($e->getMessage());
            }
        }
        $this->_redirectReferer();
        return;
    }

    protected function _copy($pageId) {
        $collection = Mage::getModel('mpanel/block')
                ->getCollection()
                ->addFieldToFilter('page_type', array('eq' => 'product'))
                ->addFieldToFilter('page_id', array('eq' => $pageId))
                ->setOrder('sort_order', 'ASC');
        if (!count($collection)) {
            $copyCollection = Mage::getModel('mpanel/block')
                    ->getCollection()
                    ->addFieldToFilter('page_type', array('eq' => 'product'))
                    ->addFieldToFilter('page_id', array('eq' => 0))
                    ->setOrder('sort_order', 'ASC');
            foreach ($copyCollection as $block) {
                $data = $block->getData();
                $model = Mage::getModel('mpanel/block');
                $model->setPageType($data['page_type']);
                $model->setPageId($pageId);
                $model->setPlace($data['place']);
                $model->setType($data['type']);
                $model->setTitle($data['title']);
                $model->setOptions($data['options']);
                $model->setSortOrder($data['sort_order']);
                $model->save();
            }
        }
    }

}
