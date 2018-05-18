<?php
class MGS_Mpanel_Model_Source_Fontsize {

    public function toOptionArray() {
		$arr = array(array('value' => '', 'label' => ''));
		for($i=8; $i<=30; $i++){
			$arr[] = array('value'=>$i.'px', 'label'=>$i.'px');
		}
		return $arr;
    }

}