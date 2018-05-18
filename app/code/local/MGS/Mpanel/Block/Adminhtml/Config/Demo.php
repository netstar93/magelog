<?php

class MGS_Mpanel_Block_Adminhtml_Config_Demo extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * Install theme
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return String
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
		$html = '';
		
		$dir = Mage::getBaseDir() . '/app/code/local/MGS/Mpanel/data/themes/';
		
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				$html .='<div class="section-config"><div class="entry-edit-head collapseable">';
				$html .='<a onclick="Fieldset.toggleCollapse(\'mgs_theme_demo\', \'\'); return false;" href="#" id="mgs_theme_demo-head" class="">'.$this->__('Import Demo Data').'</a></div>';
				$html .= '<input type="hidden" value="0" name="config_state[mgs_theme_demo]" id="mgs_theme_demo-state"><fieldset id="mgs_theme_demo" class="config collapseable" style="display: none;">';
				
				while (($file = readdir($dh)) !== false) {
					$file_parts = pathinfo($dir.$file);
					if(isset($file_parts['extension']) && $file_parts['extension']=='xml'){
						$themeName = $theme = str_replace('.xml','',$file);
						$themeName = $this->convertThemeName($themeName);
						
						$html .= '<h2 style="margin-top:5px; cursor:pointer" onclick="$(\''.$theme.'-homepage\').toggle();">'.$themeName.' Homepage</h2>';
						$html .= '<table class="form-list" cellspacing="0" id="'.$theme.'-homepage" style="margin-bottom:10px; display:none"><tbody>';
						
						
						
						$dirHome = Mage::getBaseDir() . '/app/code/local/MGS/Mpanel/data/homes/'.$theme.'/';
						$fileHomes = array();
						$themepack = Mage::getStoreConfig('mpanel/general/themepack');
						if($themepack==''){
							$themepack = 'mgstheme';
						}
						if (is_dir($dirHome)) {
							if ($dhHome = opendir($dirHome)) {
								while ($fileHomes[] = readdir($dhHome));
								sort($fileHomes);
								foreach ($fileHomes as $fileHome){
									$file_parts_home = pathinfo($dirHome.$fileHome);
									if(isset($file_parts_home['extension']) && $file_parts_home['extension']=='xml'){
										$homeName = $home = str_replace('.xml','',$fileHome);
										$homeName = $this->convertThemeName($homeName);
										
										$installUrl = $this->getUrl('adminhtml/install/demo', array('theme'=>$theme,'home'=>$home,'website'=>$this->getRequest()->getParam('website'),'store'=>$this->getRequest()->getParam('store')));
										
										$htmlConfirm = '';
										if(!$this->getRequest()->getParam('website')){
											$htmlConfirm = "if(confirm('".$this->__('Are you sure you would like to import %s to all store view?', $homeName)."')) ";
										}
										$html .= '<tr>';
										$html .= '<td style="width:250px; padding-bottom:10px; border:1px solid #d6d6d6; border-right:0; padding:10px">';
										$src = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'skin/frontend/'.$themepack.'/'.$theme.'/asset/homes/'.$home.'.jpg';
										$html .= '<img src="'.$src.'" alt="" style="max-width:100%"/>';
										$html .= '</td>';
										$html .= '<td style="padding:10px; padding-bottom:10px;  border:1px solid #d6d6d6; border-left:0">'.$this->getLayout()->createBlock('adminhtml/widget_button')
										->setType('button')
										->setLabel($this->__('Import this homepage'))
										->setOnClick($htmlConfirm."setLocation('$installUrl')")
										->toHtml();
										$html .= '</td></tr>';
										$html .= '<tr><td colspan="2" style="padding-bottom:20px"></td></tr>';
									}
								}
								$html .= '<tr><td colspan="2"><hr/></td></tr>';
								closedir($dhHome);
							}
						}
						$html .= '</tbody></table>';
					}
				}
				
				$html .= '</fieldset></div>';
				closedir($dh);
			}
		}

        return $html;
    }
	
	public function convertThemeName($theme){
		$themeName = str_replace('_',' ',$theme);
		return ucfirst($themeName);
	}
}
