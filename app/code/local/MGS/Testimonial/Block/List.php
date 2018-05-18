<?php
class MGS_Testimonial_Block_List extends Mage_Core_Block_Template {
	
	public function getTestimonial() {
		$models = Mage::getModel('testimonial/testimonial')
				->getCollection()
				->addFieldToFilter('status', 1);
		
		return $models;
	}
	public function getTestimonialsPager() {
		$p = Mage::app()->getRequest()->getParam('p');
		if($p == null) {
			$p = 1;
		}
		$count = 0;
		$i = 0;
		$tpager = array();
		if($p == 1) {
			foreach($this->getTestimonial() as $model) {
				if(!$this->getPerPage()) {
					$tpager[$i] = $model->getId();
					$i++;
				} else {
					if($i < $this->getPerPage()){
						$tpager[$i] = $model->getId();
						$i++;
					} else {
						break;
					}
				}
			}
		} else {
			foreach($this->getTestimonial() as $model) {
				$count++;
				if($count <= ($p-1)*$this->getPerPage()){
					continue;
				}
				
				if($i < $this->getPerPage()){
					$tpager[$i] = $model->getId();
					$i++;
				} else {
					break;
				}
			}
		}
		echo "<script type='text/javascript'>
			mgsjQuery(document).ready(function(){
				mgsjQuery('.pager a').each(function(){
					if(mgsjQuery(this).text() == ".$p.") {
						mgsjQuery(this).replaceWith('<strong class=". active .">".$p."</strong>');
					}
				});
			});
		</script>";
		return $tpager;
	}
	public function getCount() {
		return $this->getTestimonial()->count();
	}
	public function getPerPage() {
		return Mage::getStoreConfig('testimonial/general/testimonial_per_page');
	}
	public function getPageNumber($pager = NULL) {
		if($pager < 2) {
			return false;
		}
		for($i=1 ; $i<=$pager; $i++) {
			echo '<a href="'.Mage::getUrl("testimonial", array("_query"=>array("p"=>$i))).'">'.$i.'</a>';
		}
	}
}