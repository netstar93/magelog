<?php 
class MGS_Mpanel_Model_Observer extends Mage_Core_Model_Abstract
{
    public function addHandles($observer) {
	
		if(Mage::getStoreConfig('mpanel/general/enabled')){
			$action = $observer->getEvent()->getAction();
			$actionName = $action->getFullActionName();
			
			$actionToAddHandle = Mage::helper('mpanel')->getMyAccountActionName();
			$configLayout = Mage::getStoreConfig('mpanel/my_account/layout');
			
			if($configLayout!='' && in_array($actionName, $actionToAddHandle)){
				$layout = $observer->getEvent()->getLayout();
				
				$hander = '';
				switch ($configLayout) {
					case 'empty':
						$hander = 'mpanel_empty_column';
						break;
					case 'one_column':
						$hander = 'mpanel_one_column';
						break;
					case 'two_columns_left':
						$hander = 'mpanel_twocolumn_left';
						break;
					case 'two_columns_right':
						$hander = 'mpanel_twocolumn_right';
						break;
					case 'three_columns':
						$hander = 'mpanel_three_column';
						break;
				}
				
				$layout->getUpdate()->addHandle($hander);
			}
		}
    }
	
	public function deferJs($observer) {
		if(Mage::app()->getStore()->isAdmin() || !Mage::getStoreConfig('defer_js/general/enabled')) {
			return $this;
		}

		$response = $observer->getEvent()->getControllerAction()->getResponse();
		if(!$response) return $this;
		$html = $response->getBody();

		$condition = '#<\!--\[if[^\>]*>\s*<script.*</script>\s*<\!\[endif\]-->#isU';
		preg_match_all($condition, $html, $matches);
		$ifJs = implode('', $matches[0]);

		$html = preg_replace($condition, '' , $html);


		$condition = '@(?:<script type="text/javascript"|<script)(.*)</script>@msU';
		preg_match_all($condition,$html,$matches);
		$js = implode('',$matches[0]);

		$html = preg_replace($condition,'',$html);

		$html .= $js . $ifJs;

		$response->setBody($html);
	}
}