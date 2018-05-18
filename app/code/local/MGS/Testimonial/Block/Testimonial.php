<?php
class MGS_Testimonial_Block_Testimonial extends Mage_Core_Block_Template
{

	public function getTestimonials()     
	{
		$collection = Mage::getModel('testimonial/testimonial')
			->getCollection()
			->addFieldToFilter('status', 1);
		if($this->getItemCount()){
			$collection->setPageSize($this->getItemCount());
		}
		return $collection;
	}
}