<?php
class MGS_Mpanel_Block_Adminhtml_Fields_Fontsize extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $output = parent::_getElementHtml($element);

        ob_start();
		
			$htmlId = $element->getHtmlId();
        ?>
            
            <script type="text/javascript">
                jQuery(function(){
                    jQuery("#<?php echo $element->getHtmlId()?>").change(function(){
                        jQuery("#<?php echo str_replace('_fontsize','_view',$element->getHtmlId()) ?>").css({ fontSize: jQuery("#<?php echo $element->getHtmlId()?>").val()});
                    }).keyup(function(){
                        jQuery("#<?php echo str_replace('_fontsize','_view',$element->getHtmlId()) ?>").css({ fontSize: jQuery("#<?php echo $element->getHtmlId()?>").val()});
                    }).keydown(function(){
                        jQuery("#<?php echo str_replace('_fontsize','_view',$element->getHtmlId()) ?>").css({ fontSize: jQuery("#<?php echo $element->getHtmlId()?>").val() });
                    });
                    jQuery("#<?php echo $element->getHtmlId()?>").trigger("change");
                })
            </script>
        <?php
        $output.=ob_get_contents();
        ob_clean();
        return $output;
    }
}