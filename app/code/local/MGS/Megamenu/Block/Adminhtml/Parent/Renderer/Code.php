<?php

class MGS_Megamenu_Block_Adminhtml_Parent_Renderer_Code extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
    {
		$id = $row->getId();
		if($id>1){
			$type = 'megamenu/horizontal';
			$template = 'horizontal.phtml';
			if($row->getMenuType()==2){
				$type = 'megamenu/vertical';
				$template = 'vertical.phtml';
			}
			
			$html = '<strong>{{block type="'.$type.'" menu_id="'.$id.'" template="megamenu/'.$template.'"}}</strong>';
			return $html;
		}
		else{
			return '';
		}
    }
}