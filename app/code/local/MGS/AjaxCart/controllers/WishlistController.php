<?php

/* * ****************************************************
 * Package   : AjaxCart
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

require_once 'Mage/Wishlist/controllers/IndexController.php';

class MGS_AjaxCart_WishlistController extends Mage_Wishlist_IndexController {

    /**
     * Add wishlist item to shopping cart and remove from wishlist
     *
     * If Product has required options - item removed from wishlist and redirect
     * to product view page with message about needed defined required options
     */
    public function cartAction() {
        $response = array();
        $params = $this->getRequest()->getParams();
        if (isset($params['url'])) {
            $url = $params['url'];
            if (preg_match('/item/', $url)) {
                $arr = explode('/item/', $url);
                $itemId = explode('/', end($arr));
                $itemId = (int) $itemId[0];
            }
        }
        if (isset($params['item'])) {
            $itemId = (int) $params['item'];
        }

        /* @var $item Mage_Wishlist_Model_Item */
        $item = Mage::getModel('wishlist/item')->load($itemId);
        if (!$item->getId()) {
            $response['status'] = 'ERROR';
            $response['message'] = Mage::helper('wishlist')->__('Unable to find Item ID');
        }
        $wishlist = $this->_getWishlist($item->getWishlistId());
        if (!$wishlist) {
            $response['status'] = 'ERROR';
            $response['message'] = Mage::helper('wishlist')->__('Unable to find Wishlist');
        }

        // Set qty
        $qty = $this->getRequest()->getParam('qty');
        if (is_array($qty)) {
            if (isset($qty[$itemId])) {
                $qty = $qty[$itemId];
            } else {
                $qty = 1;
            }
        }
        $qty = $this->_processLocalizedQty($qty);
        if ($qty) {
            $item->setQty($qty);
        }

        $cart = Mage::getSingleton('checkout/cart');

        try {
            $options = Mage::getModel('wishlist/item_option')->getCollection()
                    ->addItemFilter(array($itemId));
            $item->setOptions($options->getOptionsByItem($itemId));

            $buyRequest = Mage::helper('catalog/product')->addParamsToBuyRequest(
                    $this->getRequest()->getParams(), array('current_config' => $item->getBuyRequest())
            );

            $item->mergeBuyRequest($buyRequest);
            $item->addToCart($cart, true);
            $cart->save()->getQuote()->collectTotals();
            $wishlist->save();
            $product = Mage::getModel('catalog/product')->load($item->getProductId());
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
            $top_checkout_cart = $this->getLayout()->createBlock('checkout/cart_sidebar')->setTemplate('checkout/cart/sidebar3.phtml')->toHtml();
            //$sidebar_block = $this->getLayout()->getBlock('cart_sidebar');
            if ($this->getLayout()->getBlock('customer.wishlist')) {
                $myWishlist = $this->getLayout()->getBlock('customer.wishlist')->toHtml();
                $response['my_wishlist'] = $myWishlist;
            }
            Mage::register('referrer_url', $this->_getRefererUrl());
            //$sidebar = $sidebar_block->toHtml();                
            $product_confirmation = $this->getLayout()->createBlock('ajaxcart/product_confirmation')->setProduct($product)->toHtml();
            if ($this->getLayout()->getBlock('top.links')) {
                $toplink = $this->getLayout()->getBlock('top.links')->toHtml();
                $response['toplink'] = $toplink;
            }
            $response['sidebar'] = $block_cart;
            $response['top_sider_bar'] = $top_checkout_cart;
            if ($this->getLayout()->getBlock('wishlist_sidebar')) {
                $miniWishlist = $this->getLayout()->getBlock('wishlist_sidebar')->toHtml();
                $response['mini_wishlist'] = $miniWishlist;
            }
            $response['product_confirmation'] = $product_confirmation;

            Mage::helper('wishlist')->calculate();

            Mage::helper('wishlist')->calculate();
        } catch (Mage_Core_Exception $e) {
            if ($e->getCode() == Mage_Wishlist_Model_Item::EXCEPTION_CODE_NOT_SALABLE) {
                $response['status'] = 'ERROR';
                $response['error'] = 'NOT_SALABLE';
                $response['message'] = Mage::helper('wishlist')->__('This product(s) is currently out of stock');
            } else if ($e->getCode() == Mage_Wishlist_Model_Item::EXCEPTION_CODE_HAS_REQUIRED_OPTIONS) {
                $response['status'] = 'ERROR';
                $response['error'] = 'HAS_REQUIRED_OPTIONS';
                $response['message'] = $e->getMessage();
            } else {
                $response['status'] = 'ERROR';
                $response['error'] = 'SPECIFY_PRODUCT';
                $response['message'] = $e->getMessage();
            }
        } catch (Exception $e) {
            $response['status'] = 'ERROR';
            $response['message'] = Mage::helper('wishlist')->__('Cannot add item to shopping cart');
        }

        Mage::helper('wishlist')->calculate();

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }

    public function allcartAction() {
        $response = array();
        $wishlist = $this->_getWishlist();
        if (!$wishlist) {
            //$this->_forward('noRoute');
            return;
        }
        $isOwner = $wishlist->isOwner(Mage::getSingleton('customer/session')->getCustomerId());

        $messages = array();
        $addedItems = array();
        $notSalable = array();
        $hasOptions = array();

        $cart = Mage::getSingleton('checkout/cart');
        $collection = $wishlist->getItemCollection()
                ->setVisibilityFilter();

        $qtys = $this->getRequest()->getParam('qty');
        foreach ($collection as $item) {
            /** @var Mage_Wishlist_Model_Item */
            try {
                $disableAddToCart = $item->getProduct()->getDisableAddToCart();
                $item->unsProduct();

                // Set qty
                if (isset($qtys[$item->getId()])) {
                    $qty = $this->_processLocalizedQty($qtys[$item->getId()]);
                    if ($qty) {
                        $item->setQty($qty);
                    }
                }
                $item->getProduct()->setDisableAddToCart($disableAddToCart);
                // Add to cart
                if ($item->addToCart($cart, $isOwner)) {
                    $addedItems[] = $item->getProduct();
                }
            } catch (Mage_Core_Exception $e) {
                if ($e->getCode() == Mage_Wishlist_Model_Item::EXCEPTION_CODE_NOT_SALABLE) {
                    $notSalable[] = $item;
                } else if ($e->getCode() == Mage_Wishlist_Model_Item::EXCEPTION_CODE_HAS_REQUIRED_OPTIONS) {
                    $hasOptions[] = $item;
                } else {
                    $messages[] = $this->__('%s for "%s".', trim($e->getMessage(), '.'), $item->getProduct()->getName());
                }
            } catch (Exception $e) {
                Mage::logException($e);
                $messages[] = Mage::helper('wishlist')->__('Cannot add the item to shopping cart.');
            }
        }

        if ($isOwner) {
            $indexUrl = Mage::helper('wishlist')->getListUrl($wishlist->getId());
        } else {
            $indexUrl = Mage::getUrl('wishlist/shared', array('code' => $wishlist->getSharingCode()));
        }
        if (Mage::helper('checkout/cart')->getShouldRedirectToCart()) {
            $redirectUrl = Mage::helper('checkout/cart')->getCartUrl();
        } else if ($this->_getRefererUrl()) {
            $redirectUrl = $this->_getRefererUrl();
        } else {
            $redirectUrl = $indexUrl;
        }

        if ($notSalable) {
            $products = array();
            foreach ($notSalable as $item) {
                $products[] = '"' . $item->getProduct()->getName() . '"';
            }
            $messages[] = Mage::helper('wishlist')->__('Unable to add the following product(s) to shopping cart: %s.', join(', ', $products));
        }

        if ($hasOptions) {
            $products = array();
            foreach ($hasOptions as $item) {
                $products[] = '"' . $item->getProduct()->getName() . '"';
            }
            $messages[] = Mage::helper('wishlist')->__('Product(s) %s have required options. Each of them can be added to cart separately only.', join(', ', $products));
        }

        if ($messages) {
            $isMessageSole = (count($messages) == 1);
            if ($isMessageSole && count($hasOptions) == 1) {
                $item = $hasOptions[0];
                if ($isOwner) {
                    $item->delete();
                }
                $redirectUrl = $item->getProductUrl();
            } else {
                $wishlistSession = Mage::getSingleton('wishlist/session');
                foreach ($messages as $message) {
                    //$wishlistSession->addError($message);
                }
                $redirectUrl = $indexUrl;
            }
        }

        $products = array();
        if ($addedItems) {
            // save wishlist model for setting date of last update
            try {
                $wishlist->save();
            } catch (Exception $e) {
                $response['status'] = 'ERROR';
                $response['message'] = $this->__('Cannot update wishlist');
                //Mage::getSingleton('wishlist/session')->addError($this->__('Cannot update wishlist'));
                $redirectUrl = $indexUrl;
            }

            foreach ($addedItems as $product) {
                $products[] = '"' . $product->getName() . '"';
            }

            $messages[] = Mage::helper('wishlist')->__('%d product(s) have been added to shopping cart: %s.', count($addedItems), join(', ', $products));
        }
        // save cart and collect totals
        $cart->save()->getQuote()->collectTotals();

        Mage::helper('wishlist')->calculate();

        $response['status'] = 'SUCCESS';
        //$response['message'] = implode('<br>', $messages);
        $response['message'] = '<div class="ajax-cart-message">' . Mage::helper('wishlist')->__('%d product(s) have been added to shopping cart: %s.', count($addedItems), join(', ', $products)) . '</div>';
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
        $top_checkout_cart = $this->getLayout()->createBlock('checkout/cart_sidebar')->setTemplate('checkout/cart/sidebar3.phtml')->toHtml();
        //$sidebar_block = $this->getLayout()->getBlock('cart_sidebar');
        if ($this->getLayout()->getBlock('customer.wishlist')) {
            $myWishlist = $this->getLayout()->getBlock('customer.wishlist')->toHtml();
            $response['my_wishlist'] = $myWishlist;
        }
        Mage::register('referrer_url', $this->_getRefererUrl());
        //$sidebar = $sidebar_block->toHtml();                                
        if ($this->getLayout()->getBlock('top.links')) {
            $toplink = $this->getLayout()->getBlock('top.links')->toHtml();
            $response['toplink'] = $toplink;
        }
        $response['sidebar'] = $block_cart;
        $response['top_sider_bar'] = $top_checkout_cart;
        if ($this->getLayout()->getBlock('wishlist_sidebar')) {
            $miniWishlist = $this->getLayout()->getBlock('wishlist_sidebar')->toHtml();
            $response['mini_wishlist'] = $miniWishlist;
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }

}
