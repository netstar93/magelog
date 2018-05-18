<?php

/* * ****************************************************
 * Package   : Social
 * Author    : HIEPNH
 * Copyright : (c) 2014
 * ***************************************************** */
?>
<?php

class MGS_Social_Block_Facebooklikebox extends Mage_Core_Block_Template {

    public function getFacebookLikeBox() {
        $storeId = Mage::app()->getStore()->getStoreId();
        $helper = Mage::helper('social/facebooklikebox');
        $isActived = $helper->isActived($storeId);
        if ($isActived) {
            $connection = $helper->getFacebookLikeBoxConfig('connection', $storeId);
            $pageId = $helper->getFacebookLikeBoxConfig('page_id', $storeId);
            $locale = $helper->getFacebookLikeBoxConfig('locale', $storeId);
            $width = $helper->getFacebookLikeBoxConfig('width', $storeId);
            $height = $helper->getFacebookLikeBoxConfig('height', $storeId);
            if ($helper->getFacebookLikeBoxConfig('show_header', $storeId)) {
                $showHeader = 'true';
            } else {
                $showHeader = 'false';
            }
            if ($helper->getFacebookLikeBoxConfig('show_face', $storeId)) {
                $showFace = 'true';
            } else {
                $showFace = 'false';
            }
            if ($helper->getFacebookLikeBoxConfig('show_stream', $storeId)) {
                $showStream = 'true';
            } else {
                $showStream = 'false';
            }
            return '<iframe src="http://www.facebook.com/connect/connect.php?id=' . $pageId . '&locale=' . $locale . '&connections=' . $connection . '&stream=' . $showStream . '&show_faces=' . $showFace . '&header=' . $showHeader . '&width=' . $width . '&height=' . $height . '" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:' . $width . 'px; height:' . $height . 'px;" allowTransparency="true"></iframe>';
        } else {
            return null;
        }
    }

}
