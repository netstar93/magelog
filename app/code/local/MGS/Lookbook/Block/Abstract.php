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
 
 
$imgWidth => 100
$left => x
 
 */
abstract class MGS_Lookbook_Block_Abstract extends Mage_Core_Block_Template
{   
	protected $_priceBlockDefaultTemplate = 'catalog/product/price.phtml';
	
	/**
     * Default MAP renderer type
     *
     * @var string
     */
    protected $_mapRenderer = 'msrp';
	
	/**
     * Price block array
     *
     * @var array
     */
    protected $_priceBlock = array();

    /**
     * Default price block
     *
     * @var string
     */
    protected $_block = 'catalog/product_price';

    /**
     * Tier price template
     *
     * @var string
     */
    protected $_tierPriceDefaultTemplate = 'catalog/product/view/tierprices.phtml';

    /**
     * Price types
     *
     * @var array
     */
    protected $_priceBlockTypes = array();

    public function getImageUrl($lookbook){
		return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . $lookbook->getImage();
	}
	
	public function getPinHtml($lookbook){
		$pins = $lookbook->getPins();
		$arrPin = json_decode($pins, true);
		$html = '';
		$width = Mage::getStoreConfig('lookbook/general/pin_width');
		$height = Mage::getStoreConfig('lookbook/general/pin_height');
		$background = Mage::getStoreConfig('lookbook/general/pin_background');
		$color = Mage::getStoreConfig('lookbook/general/pin_text');
		$productImageWidth = Mage::getStoreConfig('lookbook/general/popup_image_width');
		$productImageHeight = Mage::getStoreConfig('lookbook/general/popup_image_height');
		$radius = round($width/2);
		if(count($arrPin)>0){
			
			foreach($arrPin as $pin){
				$imgWidth = $pin['imgW'];
				$imgHeight = $pin['imgH'];
				$top = $pin['top'];
				$left = $pin['left'];
				$leftPercent = ($left * 100)/$imgWidth;
				$topPercent = ($top * 100)/$imgHeight;
				$html .= '<div class="pin__type pin__type--area" style="width:'. $pin['width'] .'px; height:'. $pin['height'] .'px; background:#'. $background .'; color:#'. $color .'; -webkit-border-radius:'. $radius .'px; -moz-border-radius:'. $radius .'px; border-radius:'. $radius .'px; line-height:'. $height .'px; left:'. $leftPercent .'%; top:'. $topPercent .'%">';

				$html .= '<span class="pin-label">'. $pin['label'] .'</span>';
				
				if(trim($pin['custom_text'])!=''){
					if(trim($pin['custom_label'])!=''){
						$pinTitle = $pin['custom_label']; 
					}elseif($product = $this->getProductInfo($pin['text'])){
						$pinTitle = $product->getName();
					}
					$html .= '<div class="pin__title">'.$pinTitle.'</div>';
					$html .= '<div class="pin__popup pin__popup--'.$pin['position'].' pin__popup--fade pin__popup_text_content" style="width:'.($productImageWidth + 30).'px"><div class="popup__title">'.$pinTitle.'</div><div class="popup__content">'.$pin['custom_text'].'</div></div>';
				}else{
					if($product = $this->getProductInfo($pin['text'])){
						// Product Name - Tooltip
						$html .= '<div class="pin__title">'.$product->getName().'</div>';
						$html .= '<div class="pin__popup pin__popup--'.$pin['position'].' pin__popup--fade" style="width:'. (int)($productImageWidth + 30) .'px"><div class="popup__content popup__content--product">';
						// Product Image
						$html .= '<img src="'. Mage::helper('catalog/image')->init($product, 'small_image')->resize($productImageWidth, $productImageHeight) .'" width="'.$productImageWidth.'" height="'.$productImageHeight.'" alt="" />';
						
						// Product Name
						$html .= '<h3>'.$product->getName().'</h3>';
						
						// Product Prices
						$html .= $this->getPriceHtml($product);

						// Links
						$html .= '<div><a href="'.$product->getProductUrl().'">'.$this->__('Detail').'</a><a href="'.$this->getAddToCartUrl($product).'">'.$this->__('Buy Now').'</a></div></div></div>';
						
					}
				}
				$html .= '</div>';
			}
		}
		return $html;
	}
	
	public function getProductInfo($sku){
		$product = Mage::getModel('catalog/product')->getCollection()
			->addStoreFilter(Mage::app()->getStore()->getId())
			->addAttributeToSelect('*')
			->addAttributeToFilter('status',1)
			->addAttributeToFilter('visibility',array('neq'=>1))
			->addFieldToFilter('sku', $sku)
			->getFirstItem();
		if($product->getId()){
			return $product;
		}
		return false;
	}
	
	public function getAddToCartUrl($product, $additional = array())
    {
        if (!$product->getTypeInstance(true)->hasRequiredOptions($product)) {
            return $this->helper('checkout/cart')->getAddUrl($product, $additional);
        }

        return $product->getProductUrl().'?options=cart';
    }
	
	/**
     * Return price block
     *
     * @param string $productTypeId
     * @return mixed
     */
    protected function _getPriceBlock($productTypeId)
    {
        if (!isset($this->_priceBlock[$productTypeId])) {
            $block = $this->_block;
            if (isset($this->_priceBlockTypes[$productTypeId])) {
                if ($this->_priceBlockTypes[$productTypeId]['block'] != '') {
                    $block = $this->_priceBlockTypes[$productTypeId]['block'];
                }
            }
            $this->_priceBlock[$productTypeId] = $this->getLayout()->createBlock($block);
        }
        return $this->_priceBlock[$productTypeId];
    }

    /**
     * Return Block template
     *
     * @param string $productTypeId
     * @return string
     */
    protected function _getPriceBlockTemplate($productTypeId)
    {
        if (isset($this->_priceBlockTypes[$productTypeId])) {
            if ($this->_priceBlockTypes[$productTypeId]['template'] != '') {
                return $this->_priceBlockTypes[$productTypeId]['template'];
            }
        }
        return $this->_priceBlockDefaultTemplate;
    }


    /**
     * Prepares and returns block to render some product type
     *
     * @param string $productType
     * @return Mage_Core_Block_Template
     */
    public function _preparePriceRenderer($productType)
    {
        return $this->_getPriceBlock($productType)
            ->setTemplate($this->_getPriceBlockTemplate($productType))
            ->setUseLinkForAsLowAs($this->_useLinkForAsLowAs);
    }

    /**
     * Returns product price block html
     *
     * @param Mage_Catalog_Model_Product $product
     * @param boolean $displayMinimalPrice
     * @param string $idSuffix
     * @return string
     */
    public function getPriceHtml($product, $displayMinimalPrice = false, $idSuffix = '')
    {
        $type_id = $product->getTypeId();
        if (Mage::helper('catalog')->canApplyMsrp($product)) {
            $realPriceHtml = $this->_preparePriceRenderer($type_id)
                ->setProduct($product)
                ->setDisplayMinimalPrice($displayMinimalPrice)
                ->setIdSuffix($idSuffix)
                ->toHtml();
            $product->setAddToCartUrl($this->getAddToCartUrl($product));
            $product->setRealPriceHtml($realPriceHtml);
            $type_id = $this->_mapRenderer;
        }

        return $this->_preparePriceRenderer($type_id)
            ->setProduct($product)
            ->setDisplayMinimalPrice($displayMinimalPrice)
            ->setIdSuffix($idSuffix)
            ->toHtml();
    }
    
}