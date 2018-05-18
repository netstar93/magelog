<?php

class MGS_Megamenu_Helper_Images extends Mage_Cms_Helper_Wysiwyg_Images
{
	public function getImageHtmlDeclaration($filename, $renderAsTag = false)
    {
        $fileurl = $this->getCurrentUrl() . $filename;
        $mediaPath = str_replace(Mage::getBaseUrl('media'), '', $fileurl);
        $directive = sprintf('{{media url="%s"}}', $mediaPath);
        if ($renderAsTag) {
            $html = sprintf('<img src="%s" alt="" />', $this->isUsingStaticUrlsAllowed() ? $fileurl : $directive);
        } else {
            if ($this->isUsingStaticUrlsAllowed()) {
                $html = $fileurl; // $mediaPath;
            } else {
                $directive = Mage::helper('core')->urlEncode($directive);
                $html = Mage::helper('adminhtml')->getUrl('*/cms_wysiwyg/directive', array('___directive' => $directive));
            }
        }
		$html = str_replace('url="', "url='", $html);
		$html = str_replace('"}}', "'}}", $html);
        return $html;
    }
}