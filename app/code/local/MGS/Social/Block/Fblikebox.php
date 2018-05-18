<?php

/* * ****************************************************
 * Package   : Social
 * Author    : HIEPNH
 * Copyright : (c) 2014
 * ***************************************************** */
?>
<?php

class MGS_Social_Block_Fblikebox extends Mage_Core_Block_Template {

    public function getFacebookLikeBox() {
        $pageId = $this->getPageId();
        $width = $this->getWidth();
        $height = $this->getHeight();
        if ($this->getUseSmallHeader()) {
            $useSmallHeader = 'true';
        } else {
            $useSmallHeader = 'false';
        }
        if ($this->getDataAdaptContainerWidth()) {
            $dataAdaptContainerWidth = 'true';
        } else {
            $dataAdaptContainerWidth = 'false';
        }
        if ($this->getDataHideCover()) {
            $dataHideCover = 'true';
        } else {
            $dataHideCover = 'false';
        }
        if ($this->getDataShowFacepile()) {
            $dataShowFacepile = 'true';
        } else {
            $dataShowFacepile = 'false';
        }
        if ($this->getDataShowPosts()) {
            $dataShowPosts = 'true';
        } else {
            $dataShowPosts = 'false';
        }
        if ($pageId != '' && $width != '' && $height != '') {
            return '<div class="fb-page" data-href="https://www.facebook.com/' . $pageId . '" data-width="' . $width . '" data-height="' . $height . '" data-small-header="' . $useSmallHeader . '" data-adapt-container-width="' . $dataAdaptContainerWidth . '" data-hide-cover="' . $dataHideCover . '" data-show-facepile="' . $dataShowFacepile . '" data-show-posts="' . $dataShowPosts . '"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/' . $pageId . '"><a href="https://www.facebook.com/' . $pageId . '">' . $this->getTitle() . '</a></blockquote></div></div>';
        } else {
            return null;
        }
    }

}
