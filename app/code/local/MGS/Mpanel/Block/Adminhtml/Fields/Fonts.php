<?php

class MGS_Mpanel_Block_Adminhtml_Fields_Fonts extends Mage_Adminhtml_Block_System_Config_Form_Field {

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
        $output = parent::_getElementHtml($element);

        ob_start();

        $htmlId = $element->getHtmlId();
        $el = str_replace('mgs_theme_fonts_', '', $htmlId);
        if ($el == 'h1' || $el == 'h2' || $el == 'h3' || $el == 'h4' || $el == 'h5' || $el == 'h6') {
            echo '<' . $el . ' id="' . $htmlId . '_view" style="display:block; padding:2px 0 0 0; margin:0">Heading ' . str_replace('h', '', $el) . '</' . $el . '>';
        } else {
            if ($el == 'price') {
                echo '<div class="price-box accent" style="font-size:20px; display:block; padding:2px 0 0 0; margin:0"><span class="price" id="' . $element->getHtmlId() . '_view">$289</span></div>';
            } else {
                if ($el == 'menu') {
                    echo '<div id="' . $htmlId . '_view" style="font-size:16px; display:block; padding:2px 0 0 0; margin:0">HOME</div>';
                } else {
                    echo '<span id="' . $element->getHtmlId() . '_view" style="font-size:14px; display:block; padding:2px 0 0 0; margin:0">Lorem ipsum dolor sit amet</span>';
                }
            }
        }
        ?>
        <?php
        $isSecure = Mage::app()->getStore()->isCurrentlySecure();
        ?>
        <?php if ($isSecure): ?>
            <script type="text/javascript">
                jQuery(function (){
                jQuery("#<?php echo $element->getHtmlId() ?>").change(function () {
                jQuery("#<?php echo $element->getHtmlId() ?>_view").css({fontFamily: jQuery("#<?php echo $element->getHtmlId() ?>").val().replace("+", " ")});
                        jQuery("<link />", {href: "https://fonts.googleapis.com/css?family=" + jQuery("#<?php echo $element->getHtmlId() ?>").val(), rel: "stylesheet", type: "text/css"}).appendTo("head");
                }).keyup(function () {
                jQuery("#<?php echo $element->getHtmlId() ?>_view ").css({fontFamily: jQuery("#<?php echo $element->getHtmlId() ?>").val().replace("+", " ")});
                        jQuery("<link />", {href: " https://fonts.googleapis.com/css?family=" + jQuery("#<?php echo $element->getHtmlId() ?>").val(), rel: "stylesheet", type: "text/css"}).appendTo("head");
                }).keydown(function () {
                jQuery("#<?php echo $element->getHtmlId() ?>_view").css({fontFamily: jQuery("#<?php echo $element->getHtmlId() ?>").val().replace("+", " ")});
                        jQuery("<link />", {href: "https://fonts.googleapis.com/css?family=" + jQuery("#<?php echo $element->getHtmlId() ?>").val(), rel: "stylesheet", type: "text/css"}).appendTo("head");
                });
                        jQuery("#<?php echo $element->getHtmlId() ?>").trigger("change");
                })
            </script>
        <?php else: ?>
            <script type="text/javascript">
                        jQuery(function () {
                        jQuery ("#<?php echo $element->getHtmlId() ?>").change(function() {
                        jQuery("#<?php echo $element->getHtmlId() ?>_view").css({fontFamily: jQuery("#<?php echo $element->getHtmlId() ?>").val().replace("+", " ")});
                        jQuery("<link />", {href: "http://fonts.googleapis.com/css?family=" + jQuery("#<?php echo $element->getHtmlId() ?>").val(), rel: "stylesheet", type:  "text/css"}).appendTo("head");
                        }).keyup(function () {
                        jQuery("#<?php echo $element->getHtmlId() ?>_view").css({ fontFamily: jQuery ("#<?php echo $element->getHtmlId() ?>").val().replace("+", " ")});
                        jQuery("<link />", {href: "http://fonts.googleapis.com/css?family=" + jQuery("#<?php echo $element->getHtmlId() ?>").val(), rel: "stylesheet", type: "text/css"}).appendTo("head");
                        }).keydown(function() {
                        jQuery("#<?php echo $element->getHtmlId() ?>_view").css({fontFamily: jQuery("#<?php echo $element->getHtmlId() ?>").val().replace("+", " ")});
                        jQuery("<link />", {href: "http://fonts.googleapis.com/css?family=" + jQuery("#<?php echo $element->getHtmlId() ?>").val(), rel: "stylesheet", type: "text/css"}).appendTo("head");
                        });
                                jQuery("#<?php echo $element->getHtmlId() ?>").trigger("change");
                        })
            </script>
        <?php endif; ?>

        <?php
        $output.=ob_get_contents();
        ob_clean();
        return $output;
    }

}
