<?php

/* * ****************************************************
 * Package   : AjaxCart
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

require_once 'Mage/Checkout/controllers/CartController.php';

class MGS_AjaxCart_CartController extends Mage_Checkout_CartController {

    /**
     * Add product to shopping cart action
     */
    public function addAction() {
        $cart = $this->_getCart();
        $params = $this->getRequest()->getParams();
        $response = array();
        try {
            if (isset($params['qty'])) {
                $filter = new Zend_Filter_LocalizedToNormalized(
                        array('locale' => Mage::app()->getLocale()->getLocaleCode())
                );
                $params['qty'] = $filter->filter($params['qty']);
            }
            $product = $this->_initProduct();
            $related = $this->getRequest()->getParam('related_product');
            /**
             * Check product availability
             */
            if (!$product) {
                $response['status'] = 'ERROR';
                $response['message'] = $this->__('Unable to find Product ID');
            }
            $cart->addProduct($product, $params);
            if (!empty($related)) {
                $cart->addProductsByIds(explode(',', $related));
            }
            $cart->save();
            $this->_getSession()->setCartWasUpdated(true);
            Mage::dispatchEvent('checkout_cart_add_product_complete', array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
            );
            if (!$cart->getQuote()->getHasError()) {
                $message = $this->__('%s was added to your shopping cart.', Mage::helper('core')->htmlEscape($product->getName()));
                $response['status'] = 'SUCCESS';
                $response['message'] = $message;
                $this->loadLayout();
                $update = $this->getLayout()->getUpdate();
                $update->addHandle('checkout_cart_index');
                $update->addHandle('wishlist_index_index');
                $this->loadLayoutUpdates();
                $this->generateLayoutXml();
                $this->generateLayoutXml()->generateLayoutBlocks();
                $this->generateLayoutBlocks();
                $this->_isLayoutLoaded = true;
                if ($this->getLayout()->getBlock('checkout.cart')) {
                    $block_cart = $this->getLayout()->getBlock('checkout.cart')->toHtml();
                    $response['cart'] = $block_cart;
                }
                //$this->getLayout()->getBlock('checkout.cart');
				$top_icon_cart = $this->getLayout()->createBlock('checkout/cart_sidebar')->setTemplate('page/template/topcarticon.phtml')->toHtml();                
                $top_checkout_cart = $this->getLayout()->createBlock('checkout/cart_sidebar')->setTemplate('checkout/cart/topcart.phtml')->toHtml();
				$sidebar_checkout_cart = $this->getLayout()->createBlock('checkout/cart_sidebar')->setTemplate('checkout/cart/sidebar.phtml')->toHtml();
                //$sidebar_block = $this->getLayout()->getBlock('cart_sidebar');
                if ($this->getLayout()->getBlock('customer.wishlist')) {
                    $myWishlist = $this->getLayout()->getBlock('customer.wishlist')->toHtml();
                    $response['my_wishlist'] = $myWishlist;
                }
                //$this->getLayout()->createBlock('wishlist/customer_wishlist')->setTemplate('mgs/ajaxcart/wishlist/view.phtml')->toHtml();
                Mage::register('referrer_url', $this->_getRefererUrl());
                //$sidebar = $sidebar_block->toHtml();                
                $product_confirmation = $this->getLayout()->createBlock('ajaxcart/product_confirmation')->setProduct($product)->toHtml();
                if ($this->getLayout()->getBlock('top.links')) {
                    $toplink = $this->getLayout()->getBlock('top.links')->toHtml();
                    $response['toplink'] = $toplink;
                }
                $response['sidebar'] = $block_cart;
                $response['top_cart'] = $top_checkout_cart;
				$response['top_cart_icon'] = $top_icon_cart;
				$response['sidebar_cart'] = $sidebar_checkout_cart;
                if ($this->getLayout()->getBlock('wishlist_sidebar')) {
                    $miniWishlist = $this->getLayout()->getBlock('wishlist_sidebar')->toHtml();
                    $response['mini_wishlist'] = $miniWishlist;
                }
                //$response['my_wishlist'] = $myWishlist;                
                $response['product_confirmation'] = $product_confirmation;
            }
        } catch (Mage_Core_Exception $e) {
            $msg = '';
            if ($this->_getSession()->getUseNotice(true)) {
                $msg = $e->getMessage();
            } else {
                $messages = array_unique(explode('\n', $e->getMessage()));
                foreach ($messages as $message) {
                    $msg .= $message . '<br/>';
                }
            }
            $response['status'] = 'ERROR';
            $response['message'] = $msg;
        } catch (Exception $e) {
            $response['status'] = 'ERROR';
            $response['message'] = $this->__('Cannot add the item to shopping cart.');
            Mage::logException($e);
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }

    /**
     * Update product configuration for a cart item
     */
    public function updateItemOptionsAction() {
        $cart = $this->_getCart();
        $id = (int) $this->getRequest()->getParam('id');
        $params = $this->getRequest()->getParams();
        $response = array();

        if (!isset($params['options'])) {
            $params['options'] = array();
        }
        try {
            if (isset($params['qty'])) {
                $filter = new Zend_Filter_LocalizedToNormalized(
                        array('locale' => Mage::app()->getLocale()->getLocaleCode())
                );
                $params['qty'] = $filter->filter($params['qty']);
            }

            $quoteItem = $cart->getQuote()->getItemById($id);
            if (!$quoteItem) {
                $response['status'] = 'ERROR';
                $response['message'] = $this->__('Quote item is not found.');
            }

            $item = $cart->updateItem($id, new Varien_Object($params));
            if (is_string($item)) {
                $response['status'] = 'ERROR';
                $response['message'] = $item;
            }
            if ($item->getHasError()) {
                $response['status'] = 'ERROR';
                $response['message'] = $item->getMessage();
            }

            $related = $this->getRequest()->getParam('related_product');
            if (!empty($related)) {
                $cart->addProductsByIds(explode(',', $related));
            }

            $cart->save();

            $this->_getSession()->setCartWasUpdated(true);

            Mage::dispatchEvent('checkout_cart_update_item_complete', array('item' => $item, 'request' => $this->getRequest(), 'response' => $this->getResponse())
            );
            if (!$this->_getSession()->getNoCartRedirect(true)) {
                if (!$cart->getQuote()->getHasError()) {
                    $message = $this->__('%s was updated in your shopping cart.', Mage::helper('core')->htmlEscape($item->getProduct()->getName()));
                    $response['status'] = 'SUCCESS';
                    $response['message'] = $message;
                    $this->loadLayout();
                    $update = $this->getLayout()->getUpdate();
                    $update->addHandle('checkout_cart_index');
                    $update->addHandle('wishlist_index_index');
                    $this->loadLayoutUpdates();
                    $this->generateLayoutXml();
                    $this->generateLayoutXml()->generateLayoutBlocks();
                    $this->generateLayoutBlocks();
                    $this->_isLayoutLoaded = true;
                    if ($this->getLayout()->getBlock('checkout.cart')) {
                        $block_cart = $this->getLayout()->getBlock('checkout.cart')->toHtml();
                        $response['cart'] = $block_cart;
                    }
                    //$this->getLayout()->getBlock('checkout.cart');                
                    $top_checkout_cart = $this->getLayout()->createBlock('checkout/cart_sidebar')->setTemplate('checkout/cart/topcart.phtml')->toHtml();
					$top_icon_cart = $this->getLayout()->createBlock('checkout/cart_sidebar')->setTemplate('page/template/topcarticon.phtml')->toHtml();
					$sidebar_checkout_cart = $this->getLayout()->createBlock('checkout/cart_sidebar')->setTemplate('checkout/cart/sidebar.phtml')->toHtml();
                    //$sidebar_block = $this->getLayout()->getBlock('cart_sidebar');
                    if ($this->getLayout()->getBlock('customer.wishlist')) {
                        $myWishlist = $this->getLayout()->getBlock('customer.wishlist')->toHtml();
                        $response['my_wishlist'] = $myWishlist;
                    }
                    //$this->getLayout()->createBlock('wishlist/customer_wishlist')->setTemplate('mgs/ajaxcart/wishlist/view.phtml')->toHtml();
                    Mage::register('referrer_url', $this->_getRefererUrl());
                    //$sidebar = $sidebar_block->toHtml();                
                    $product_confirmation = $this->getLayout()->createBlock('ajaxcart/product_confirmation')->setProduct($product)->toHtml();
                    if ($this->getLayout()->getBlock('top.links')) {
                        $toplink = $this->getLayout()->getBlock('top.links')->toHtml();
                        $response['toplink'] = $toplink;
                    }
                    $response['sidebar'] = $block_cart;
                    $response['top_cart'] = $top_checkout_cart;
					$response['top_cart_icon'] = $top_icon_cart;
					$response['sidebar_cart'] = $sidebar_checkout_cart;
                    if ($this->getLayout()->getBlock('wishlist_sidebar')) {
                        $miniWishlist = $this->getLayout()->getBlock('wishlist_sidebar')->toHtml();
                        $response['mini_wishlist'] = $miniWishlist;
                    }
                    $response['product_confirmation'] = $product_confirmation;
                }
            }
        } catch (Mage_Core_Exception $e) {
            $msg = '';
            if ($this->_getSession()->getUseNotice(true)) {
                $msg = $e->getMessage();
            } else {
                $messages = array_unique(explode('\n', $e->getMessage()));
                foreach ($messages as $message) {
                    $msg .= $message . '<br/>';
                }
            }
            $response['status'] = 'ERROR';
            $response['message'] = $msg;
        } catch (Exception $e) {
            $response['status'] = 'ERROR';
            $response['message'] = $this->__('Cannot update the item.');
            Mage::logException($e);
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }

    public function addgroupAction() {
        $response = array();
        $orderItemIds = $this->getRequest()->getParam('order_items', array());
        if (is_array($orderItemIds)) {
            $itemsCollection = Mage::getModel('sales/order_item')
                    ->getCollection()
                    ->addIdFilter($orderItemIds)
                    ->load();
            /* @var $itemsCollection Mage_Sales_Model_Mysql4_Order_Item_Collection */
            $cart = $this->_getCart();
            foreach ($itemsCollection as $item) {
                try {
                    $cart->addOrderItem($item, 1);
                } catch (Mage_Core_Exception $e) {
                    if ($this->_getSession()->getUseNotice(true)) {
                        $response['status'] = 'ERROR';
                        $response['message'] = $e->getMessage();
                    } else {
                        $response['status'] = 'ERROR';
                        $response['message'] = $e->getMessage();
                    }
                } catch (Exception $e) {
                    $response['status'] = 'ERROR';
                    $response['message'] = $this->__('Cannot add the item to shopping cart.');
                    Mage::logException($e);
                }
            }
            $cart->save();
            $this->_getSession()->setCartWasUpdated(true);
            $message = $this->__('Item(s) was added in your shopping cart.');
            $response['status'] = 'SUCCESS';
            $response['message'] = $message;
            $this->loadLayout();
            $update = $this->getLayout()->getUpdate();
            $update->addHandle('checkout_cart_index');
            $update->addHandle('wishlist_index_index');
            $this->loadLayoutUpdates();
            $this->generateLayoutXml();
            $this->generateLayoutXml()->generateLayoutBlocks();
            $this->generateLayoutBlocks();
            $this->_isLayoutLoaded = true;
            if ($this->getLayout()->getBlock('checkout.cart')) {
                $block_cart = $this->getLayout()->getBlock('checkout.cart')->toHtml();
                $response['cart'] = $block_cart;
            }
            //$this->getLayout()->getBlock('checkout.cart');                
			$top_icon_cart = $this->getLayout()->createBlock('checkout/cart_sidebar')->setTemplate('page/template/topcarticon.phtml')->toHtml();
            $top_checkout_cart = $this->getLayout()->createBlock('checkout/cart_sidebar')->setTemplate('checkout/cart/topcart.phtml')->toHtml();
			$sidebar_checkout_cart = $this->getLayout()->createBlock('checkout/cart_sidebar')->setTemplate('checkout/cart/sidebar.phtml')->toHtml();
            //$sidebar_block = $this->getLayout()->getBlock('cart_sidebar');
            if ($this->getLayout()->getBlock('customer.wishlist')) {
                $myWishlist = $this->getLayout()->getBlock('customer.wishlist')->toHtml();
                $response['my_wishlist'] = $myWishlist;
            }
            //$this->getLayout()->createBlock('wishlist/customer_wishlist')->setTemplate('mgs/ajaxcart/wishlist/view.phtml')->toHtml();
            Mage::register('referrer_url', $this->_getRefererUrl());
            //$sidebar = $sidebar_block->toHtml();                            
            if ($this->getLayout()->getBlock('top.links')) {
                $toplink = $this->getLayout()->getBlock('top.links')->toHtml();
                $response['toplink'] = $toplink;
            }
            $response['sidebar'] = $block_cart;
            $response['top_cart'] = $top_checkout_cart;
			$response['top_cart_icon'] = $top_icon_cart;
			$response['sidebar_cart'] = $sidebar_checkout_cart;
            if ($this->getLayout()->getBlock('wishlist_sidebar')) {
                $miniWishlist = $this->getLayout()->getBlock('wishlist_sidebar')->toHtml();
                $response['mini_wishlist'] = $miniWishlist;
            }
            //$response['my_wishlist'] = $myWishlist;            
            $myorders_confirmation = $this->getLayout()->createBlock('ajaxcart/product_myorders')->toHtml();
            $response['myorders_confirmation'] = $myorders_confirmation;
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }

    /**
     * Delete shoping cart item action
     */
    public function deleteAction() {
        $response = array();
        $params = $this->getRequest()->getParams();
        $url = $params['url'];
        if (preg_match('/id/', $url)) {
            $arr = explode('id/', $url);
            $id = explode('/', end($arr));
            $id = (int) $id[0];
        } elseif (preg_match('/item/', $url)) {
            $arr = explode('/item/', $url);
            $itemId = explode('/', end($arr));
            $itemId = (int) $itemId[0];
            $item = Mage::getModel('wishlist/item')->load($itemId);
            $id = $item->getProductId();
        } else {
            $parts = parse_url($url);
            $arr = explode('/', $parts['path']);
            $urlPath = end($arr);
            $p = Mage::getModel('catalog/product')->getCollection()
                    ->addAttributeToSelect('*')
                    ->addFieldToFilter('url_path', $urlPath)
                    ->getFirstItem();
            $id = (int) $p->getId();
        }
        if ($id) {
            try {
                $items = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
                foreach ($items as $item) {
                    if ($item->getId() == $id) {
                        $productId = $item->getProductId();
                        break;
                    }
                }
                $this->_getCart()->removeItem($id)->save();
                $this->_getSession()->setCartWasUpdated(true);
                if (!$this->_getCart()->getQuote()->getHasError()) {
                    $product = Mage::getModel('catalog/product')->load($productId);
                    $message = $this->__('%s was deleted from your shopping cart.', Mage::helper('core')->htmlEscape($product->getName()));
                    $response['status'] = 'SUCCESS';
                    $response['message'] = $message;
                    $this->loadLayout();
                    $update = $this->getLayout()->getUpdate();
                    $update->addHandle('checkout_cart_index');
                    $update->addHandle('wishlist_index_index');
                    $this->loadLayoutUpdates();
                    $this->generateLayoutXml();
                    $this->generateLayoutXml()->generateLayoutBlocks();
                    $this->generateLayoutBlocks();
                    $this->_isLayoutLoaded = true;
                    if ($this->getLayout()->getBlock('checkout.cart')) {
                        $block_cart = $this->getLayout()->getBlock('checkout.cart')->toHtml();
                        $response['cart'] = $block_cart;
                    }
                    //$this->getLayout()->getBlock('checkout.cart');                
                    $top_checkout_cart = $this->getLayout()->createBlock('checkout/cart_sidebar')->setTemplate('checkout/cart/topcart.phtml')->toHtml();
					$top_icon_cart = $this->getLayout()->createBlock('checkout/cart_sidebar')->setTemplate('page/template/topcarticon.phtml')->toHtml();
					$sidebar_checkout_cart = $this->getLayout()->createBlock('checkout/cart_sidebar')->setTemplate('checkout/cart/sidebar.phtml')->toHtml();
                    //$sidebar_block = $this->getLayout()->getBlock('cart_sidebar');
                    if ($this->getLayout()->getBlock('customer.wishlist')) {
                        $myWishlist = $this->getLayout()->getBlock('customer.wishlist')->toHtml();
                        $response['my_wishlist'] = $myWishlist;
                    }
                    //$this->getLayout()->createBlock('wishlist/customer_wishlist')->setTemplate('mgs/ajaxcart/wishlist/view.phtml')->toHtml();
                    Mage::register('referrer_url', $this->_getRefererUrl());
                    //$sidebar = $sidebar_block->toHtml();                            
                    if ($this->getLayout()->getBlock('top.links')) {
                        $toplink = $this->getLayout()->getBlock('top.links')->toHtml();
                        $response['toplink'] = $toplink;
                    }
                    $response['sidebar'] = $block_cart;
                    $response['top_cart'] = $top_checkout_cart;
					$response['top_cart_icon'] = $top_icon_cart;
					$response['sidebar_cart'] = $sidebar_checkout_cart;
                    if ($this->getLayout()->getBlock('wishlist_sidebar')) {
                        $miniWishlist = $this->getLayout()->getBlock('wishlist_sidebar')->toHtml();
                        $response['mini_wishlist'] = $miniWishlist;
                    }
                    //$response['my_wishlist'] = $myWishlist;                    
                    $product_deletion = $this->getLayout()->createBlock('ajaxcart/product_deletion')->setProduct($product)->toHtml();
                    $response['product_deletion'] = $product_deletion;
                }
            } catch (Exception $e) {
                $response['status'] = 'ERROR';
                $response['message'] = $this->__('Cannot remove the item.');
                Mage::logException($e);
            }
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }

}
