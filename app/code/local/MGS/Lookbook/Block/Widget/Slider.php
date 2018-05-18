<?php
class MGS_Lookbook_Block_Widget_Slider extends MGS_Lookbook_Block_Abstract implements Mage_Widget_Block_Interface
{
    /**
     * Storage for used widgets
     *
     * @var array
     */
    static protected $_widgetUsageMap = array();
	
	protected $_slider;

    /**
     * Prepare block text and determine whether block output enabled or not
     * Prevent blocks recursion if needed
     *
     * @return Mage_Cms_Block_Widget_Block
     */
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();
        $sliderId = $this->getData('slider_id');
        $blockHash = get_class($this) . $sliderId;

        if (isset(self::$_widgetUsageMap[$blockHash])) {
            return $this;
        }
        self::$_widgetUsageMap[$blockHash] = true;

        if ($sliderId) {
            $slider = Mage::getModel('lookbook/slide')
                ->load($sliderId);
            if ($slider->getStatus()==1) {
				$lookbooks = Mage::getModel('lookbook/lookbook')
					->getCollection()
					->addFieldToFilter('status', 1);
					
				$lookbooks->addSliderFilter($sliderId);
				
				if(count($lookbooks)>0){
					$this->setLookbooks($lookbooks);
				}
				
                $this->setSlider($slider);
				$this->_slider = $slider;
            }
        }

        unset(self::$_widgetUsageMap[$blockHash]);
        return $this;
    }
	
	/* Show slider navigation or not */
	public function getNavigation(){
		$value = $this->_slider->getNavigation();
		if(!$value){
			$value = Mage::getStoreConfig('lookbook/slider/navigation');
		}
		return $this->convertValue($value);
	}
	
	/* Show slider pagination or not */
	public function getPagination(){
		$value = $this->_slider->getPagination();
		if(!$value){
			$value = Mage::getStoreConfig('lookbook/slider/pagination');
		}
		return $this->convertValue($value);
	}
	
	/* Autoplay or not */
	public function getAutoPlay(){
		$value = $this->_slider->getAutoPlay();
		if(!$value){
			$value = Mage::getStoreConfig('lookbook/slider/auto_play');
		}
		return $this->convertValue($value);
	}
	
	/* Autoplay timeout */
	public function getAutoplayTimeout(){
		$value = $this->_slider->getAutoPlayTimeout();
		if($value==''){
			$value = Mage::getStoreConfig('lookbook/slider/auto_play_timeout');
		}
		return $value;
	}
	
	/* Stop autoplay when mouseover */
	public function getStopAuto(){
		$value = $this->_slider->getStopAuto();
		if(!$value){
			$value = Mage::getStoreConfig('lookbook/slider/stop_auto');
		}
		return $this->convertValue($value);
	}
	
	/* Loop or not */
	public function getLoop(){
		$value = $this->_slider->getLoop();
		if(!$value){
			$value = Mage::getStoreConfig('lookbook/slider/loop');
		}
		return $this->convertValue($value);
	}
	
	/* next Icon Url */
	public function getNextIcon(){
		$value = $this->_slider->getNextImage();
		if($value == ''){
			$value = Mage::getStoreConfig('lookbook/slider/next_image');
		}
		
		return $value;
	}
	
	/* Previous Icon Url */
	public function getPrevIcon(){
		$value = $this->_slider->getPrevImage();
		if($value == ''){
			$value = Mage::getStoreConfig('lookbook/slider/prev_image');
		}
		
		return $value;
	}
	
	/* Convert to owl carousel option value: true/false */
	public function convertValue($value){
		if($value == 1){
			return 'true';
		}
		return 'false';
	}
	
	public function getControlImageUrl($value){
		return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . $value;
	}
}
