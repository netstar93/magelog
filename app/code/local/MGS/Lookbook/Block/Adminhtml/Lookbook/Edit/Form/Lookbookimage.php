<?php
/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 */
class MGS_Lookbook_Block_Adminhtml_Lookbook_Edit_Form_Lookbookimage extends Varien_Data_Form_Element_Abstract
{
    public function __construct($data)
    {
        parent::__construct($data);
        $this->setType('hidden');
    }

 public function getElementHtml()
 {
    $block_class =  Mage::getBlockSingleton('lookbook/adminhtml_lookbook');
    $upload_action  = Mage::getUrl('adminhtml/lookbook/upload', array('_secure'=>true, 'isAjax'=>'true'));
    $media_url  = Mage::getBaseUrl('media');
    $upload_folder_path = str_replace("/",DS, Mage::getBaseDir("media").DS);
    $helper = Mage::helper('lookbook');

    $sizeLimit      = $helper->getMaxUploadFilesize();

    $allowed_extensions = implode('","',explode(',',$helper->getAllowedExtensions()));
    
    $html = '<script type="text/javascript">
                //<![CDATA[
                jQuery(document).ready(function() { 
                    
                  InitHotspotBtn(); 
                  
                    img_uploader = new qq.FileUploader({
                        element: document.getElementById(\'maket_image\'),
                        action: "'.$upload_action.'",
                        params: {"form_key":"'.$block_class->getFormKey().'"},
                        multiple: false,
                        allowedExtensions: ["'.$allowed_extensions.'"],
                        sizeLimit: '. $sizeLimit .',
                        onComplete: function(id, fileName, responseJSON){                           
                                    if (responseJSON.success) 
                                    {
                                        if (jQuery(\'#LookbookImageBlock\')) 
                                        {
                                          jQuery.each(jQuery(\'#LookbookImageBlock\').children(),function(index) {
                                            jQuery(this).remove();
                                          });
                                        }
                                       jQuery(\'#LookbookImageBlock\').append(\'<img id="LookbookImage"';
                                       $html .= ' src="'.$media_url.'lookbook/\'+responseJSON.filename+\'" alt="\'+responseJSON.filename+\'"'; 
                                       $html .= ' width="\'+responseJSON.dimensions.width+\'" height="\'+responseJSON.dimensions.height+\'"/>\');
                                       
                                        if (jQuery(\'#advice-required-entry-image\')) 
                                        {
                                            jQuery(\'#advice-required-entry-image\').remove();
                                        }
                                        jQuery(\'#LookbookImage\').load(function(){
                                           jQuery(this).attr(\'width\',responseJSON.dimensions.width);
                                           jQuery(this).attr(\'height\',responseJSON.dimensions.height);
                                           InitHotspotBtn();
                                        });                       
                                        jQuery(\'#image\').val(\'lookbook/\'+responseJSON.filename);
                                        jQuery(\'#image\').removeClass(\'validation-failed\');
                                    }

                        }
                    });    
                });
                //]]>
                </script>
                <div id="LookbookImageBlock">';
                
        if ($this->getValue()) {
            $img_src = $media_url.$this->getValue();
            $img_path = $upload_folder_path.$this->getValue();
            if (file_exists($img_path)) {
                                
                $dimensions = Mage::helper('lookbook')->getImageDimensions($img_path);
                
                $html .= '<img id="LookbookImage" src="'.$img_src.'" alt="'.basename($img_src).'" width="'.$dimensions['width'].'" height="'.$dimensions['height'].'"/>';
            }
            else
            {
                $html .= '<h4 id="LookbookImage" style="color:red;">File '.$img_src.' doesn\'t exists.</h4>';
            }     
        }

        $html .= '</div>
                <div id="maket_image">       
                    <noscript>          
                        <p>Please enable JavaScript to use file uploader.</p>
                        <!-- or put a simple form for upload here -->
                    </noscript>         
                </div>';
                
        $html.= parent::getElementHtml();
        

        $html.= '<p class="note" style="clear:both; float:left;">Allowed file extensions: ' . $helper->getAllowedExtensions() . '</p>';

        
        return $html;
 }
}