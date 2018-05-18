<?php

class MGS_Mpanel_Block_Adminhtml_Config_Button extends Mage_Adminhtml_Block_System_Config_Form_Field
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
				while (($file = readdir($dh)) !== false) {
					$file_parts = pathinfo($dir.$file);
					if(isset($file_parts['extension']) && $file_parts['extension']=='xml'){
						$themeName = $theme = str_replace('.xml','',$file);
						$themeName = $this->convertThemeName($themeName);
						$installUrl = $this->getUrl('adminhtml/install/index', array('theme'=>$theme,'website'=>$this->getRequest()->getParam('website'),'store'=>$this->getRequest()->getParam('store')));
						
						$restoreUrl = $this->getUrl('adminhtml/install/restore', array('theme'=>$theme,'website'=>$this->getRequest()->getParam('website'),'store'=>$this->getRequest()->getParam('store')));
						
						$html .= '<p>';
						$html .= $this->getLayout()->createBlock('adminhtml/widget_button')
						->setType('button')
						->setLabel($this->__('Install %s Theme (v1.2)', $themeName))
						->setOnClick("setLocation('$installUrl')")
						->toHtml();
						
						if(!$this->getRequest()->getParam('website')){
							$html .= ' '.$this->getLayout()->createBlock('adminhtml/widget_button')
							->setType('button')
							->setLabel($this->__('Restore Config'))
							->setOnClick("setLocation('$restoreUrl')")
							->toHtml();
						}
						
						$html .= '</p>';
					}
				}
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
