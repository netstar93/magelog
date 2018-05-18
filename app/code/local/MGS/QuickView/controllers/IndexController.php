<?php

/* * ****************************************************
 * Package   : QuickView
 * Author    : HIEPNH
 * Copyright : (c) 2014
 * ***************************************************** */
?>
<?php

class MGS_QuickView_IndexController extends Mage_Core_Controller_Front_Action {

    public function showAction() {
        try {
            $storeId = Mage::app()->getStore()->getStoreId();
            $params = $this->getRequest()->getParams();
            if (isset($params['url'])) {
                $url = $params['url'];
                if (preg_match('/product/', $url)) {
                    $arr = explode('catalog/product/view/id/', $url);
                    $id = explode('/', end($arr));
                    $id = (int) $id[0];
                    if ($id == 0) {
                        $parts = parse_url($url);
                        $arr = explode('/', $parts['path']);
                        $urlPath = end($arr);
                        $p = Mage::getModel('catalog/product')->getCollection()
                                ->addAttributeToSelect('*')
                                ->addFieldToFilter('url_path', $urlPath)
                                ->getFirstItem();
                        $id = (int) $p->getId();
                    }
                } else {
                    $parts = parse_url($url);
                    $arr = explode('/', $parts['path']);
					$urlPath = str_replace(Mage::getBaseUrl(),'',$url);
                    //$urlPath = end($arr);
                    $collection = Mage::getModel('core/url_rewrite')->getCollection()
                            ->addFieldToFilter('request_path', array('eq' => $urlPath))
                            ->addFieldToFilter('store_id', array('eq' => $storeId));
					//echo count($collection); die();
                    $id = (int) $collection->getFirstItem()->getProductId();
                }
            }
            $product = Mage::getModel('catalog/product')->load($id);
            /**
             * Check product availability
             */
            if (!$product->getId()) {
                echo $this->__('Unable to find Product ID.');
                return;
            } else {
                $productId = $product->getId();
                // Prepare helper and params
                $viewHelper = Mage::helper('catalog/product_view');
                $params = new Varien_Object();
                $params->setCategoryId(false);
                $params->setSpecifyOptions(false);
                // Render page
                try {
                    $viewHelper->prepareAndRender($productId, $this, $params);
                } catch (Exception $ex) {
                    if ($ex->getCode() == $viewHelper->ERR_NO_PRODUCT_LOADED) {
                        if (isset($_GET['store']) && !$this->getResponse()->isRedirect()) {
                            $this->_redirect('');
                        } elseif (!$this->getResponse()->isRedirect()) {
                            echo $this->__('Unable to find Product ID.');
                            return;
                        }
                    } else {
                        Mage::logException($ex);
                        echo $this->__('Unable to find Product ID.');
                        return;
                    }
                }
            }
        } catch (Exception $ex) {
            echo $this->__('Unable to find Product ID.');
            return;
        }
    }

}
