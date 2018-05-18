<?php

class MGS_Social_Block_Twitter_Close extends Mage_Core_Block_Abstract {

    protected function _tohtml() {
        $html = '<script type="text/javascript">
        window.onload = function() {
          window.opener.location="' . $this->getRedirectUrl() . '";
          window.close();
        }
        </script>';
        return $html;
    }

}
