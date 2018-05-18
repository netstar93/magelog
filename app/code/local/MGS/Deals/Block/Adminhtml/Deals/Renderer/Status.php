<?php

class MGS_Deals_Block_Adminhtml_Deals_Renderer_Status extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
    {
		$status = $row->getStatus();
		
		switch ($status) {
			case 1:
				$class = 'minor';
				$message = 'Processing';
				break;
			case 2:
				$class = 'notice';
				$message = 'Running';
				break;
			default:
				$class = 'critical';
				$message = 'Done';
				break;
		}
		$html ='<span class="grid-severity-'.$class.'"><span>'.$message.'</span></span>';
    	return $html;
    }
}