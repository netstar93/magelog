<?php

class MGS_Deals_Block_Adminhtml_Catalog_Product_Widget_Chooser extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_selectedProducts = array();
	protected $_selectedChkbox = "";
    /**
     * Block construction, prepare grid params
     *
     * @param array $arguments Object data
     */
    public function __construct()
	{
      parent::__construct();
      $this->setSaveParametersInSession(true);
	  $this->setUseAjax(true);
	}

    /**
     * Prepare chooser element HTML
     *
     * @param Varien_Data_Form_Element_Abstract $element Form Element
     * @return Varien_Data_Form_Element_Abstract
     */
     public function prepareElementHtml(Varien_Data_Form_Element_Abstract $element)
    { 
		
        $uniqId = Mage::helper('core')->uniqHash($element->getId());
        $sourceUrl = $this->getUrl('deals/adminhtml_widget/chooser', array(
            'uniq_id' => $uniqId,
            'use_massaction' => false,
        ));
		
        $chooser = $this->getLayout()->createBlock('deals/adminhtml_catalog_product_widget_chooser')
            ->setElement($element)
            ->setTranslationHelper($this->getTranslationHelper())
            ->setConfig($this->getConfig())
            ->setFieldsetId($this->getFieldsetId())
            ->setSourceUrl($sourceUrl)
            ->setUniqId($uniqId);

        if ($element->getValue()) {
            $value = explode('/', $element->getValue());
            $productId = false;
            if (isset($value[0]) && isset($value[1]) && $value[0] == 'product') {
                $productId = $value[1];
            }
            $categoryId = isset($value[2]) ? $value[2] : false;
            $label = '';
            if ($categoryId) {
                $label = Mage::getResourceSingleton('catalog/category')
                    ->getAttributeRawValue($categoryId, 'name', Mage::app()->getStore()) . '/';
            }
            if ($productId) {
                $label .= Mage::getResourceSingleton('catalog/product')
                    ->getAttributeRawValue($productId, 'name', Mage::app()->getStore());
            }
            $chooser->setLabel($label);
        }

        $element->setData('after_element_html', $chooser->toHtml());
        return $element;
    }
	

    /**
     * Checkbox Check JS Callback
     *
     * @return string
     */
	
	public function getCheckboxCheckCallback()
	{
		$chooserJsObject = $this->getId();
			 return 'function (grid, element) {
					var gridTable = '.$chooserJsObject.'.parentNode.parentNode.parentNode;
					var hiddenRow = gridTable.lastChild;
					while (hiddenRow && hiddenRow.nodeType !== 1) {
						hiddenRow = hiddenRow.previousSibling;
					}
					var hiddenInput = hiddenRow.down("input");
					var checkboxes = document.getElementsByName("in_products[]");
					var checkstring = "";
					for (var i=1, n=checkboxes.length;i<n;i++) {
						if ((checkboxes[i].checked)) {
							if (checkstring == "") 
								checkstring = checkboxes[i].value;
							else
								checkstring += ","+ checkboxes[i].value;
						}
					}
					hiddenInput.value = checkstring;
			}';   
	}

    /**
     * Grid Row JS Callback
     *
     * @return string
     */
    public function getRowClickCallback()
    {
            $chooserJsObject = $this->getId();
            return '	
                function (grid, event) {
					if (event.target.type == "checkbox"){
						var gridTable = '.$chooserJsObject.'.parentNode.parentNode.parentNode;
						var hiddenRow = gridTable.lastChild;
						while (hiddenRow && hiddenRow.nodeType !== 1) {
							hiddenRow = hiddenRow.previousSibling;
						}
						var hiddenInput = hiddenRow.down("input");

						var checkboxes = document.getElementsByName("in_products[]");
						var checkstring = "";
						for (var i=1, n=checkboxes.length;i<n;i++) {
							if ((checkboxes[i].checked)) {
								if (checkstring == "") 
									checkstring = checkboxes[i].value;
								else
									checkstring += ","+ checkboxes[i].value;
							}
						}
						hiddenInput.value = checkstring;
					}
				}
            ';  
    }
 
    /**
     * Filter checked/unchecked rows in grid
     *
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return Mage_Adminhtml_Block_Catalog_Product_Widget_Chooser
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'deals_id') {
            $selected = $this->getSelectedProducts();
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('deals_id', array('in'=>$selected));
            } else {
                $this->getCollection()->addFieldToFilter('deals_id', array('nin'=>$selected));
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * Prepare products collection, defined collection filters (category, product type)
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
	{
		echo '<script type="text/javascript">'
		, '	var gridTable = '.$this->getId().'.parentNode.parentNode.parentNode;
			var hiddenRow = gridTable.lastChild;
			while (hiddenRow && hiddenRow.nodeType !== 1) {
				hiddenRow = hiddenRow.previousSibling;
			}
			var hiddenInput = hiddenRow.down("input").value;
			var checkArr = hiddenInput.split(",");
			var checkboxes = document.getElementsByName("in_products[]");
			for (var i=1, n=checkboxes.length;i<n;i++) {
				var x = checkboxes[i].value;
				if (checkArr.indexOf(x) != -1) {
					checkboxes[i].checked = true;
				}
			}'
		, '</script>';
      $collection = Mage::getModel('deals/deals')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
	}

    /**
     * Prepare columns for products grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _getStore()
	{
		$storeId = (int) $this->getRequest()->getParam('store', 0);
		return Mage::app()->getStore($storeId);
	}
    /**
     * Prepare columns for products grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {

        $this->addColumn('in_products[]', array(
			'header_css_class' => 'a-center',
			'type'      => 'checkbox',
			'name'      => 'in_products[]',
			
			'field_name' => 'in_products[]',
			'align'     => 'center',
			'index'     => 'deals_id',
			'use_index' => true,
        ));

        /* $this->addColumn('deals_id', array(
			'header'    => Mage::helper('deals')->__('ID'),
			'align'     =>'center',
			'width'     => '50px',
			'index'     => 'deals_id',
		)); */

		$this->addColumn('product_name', array(
			'header'    => Mage::helper('deals')->__('Product'),
			'align'     =>'left',
			'width'     => '300px',
			'index'     => 'product_name',
		));
		$store = $this->_getStore();
		$this->addColumn('price',array(
			'header'=> Mage::helper('catalog')->__('Price'),
			'type'  => 'price',
			'currency_code' => $store->getBaseCurrency()->getCode(),
			'width'     => '150px',
			'index' => 'price',
		));
		
		$this->addColumn('special_price',array(
			'header'=> Mage::helper('catalog')->__('Deal Price'),
			'type'  => 'price',
			'currency_code' => $store->getBaseCurrency()->getCode(),
			'width'     => '150px',
			'index' => 'special_price',
		));
		
		$this->addColumn('deal_qty', array(
			'header'    => Mage::helper('deals')->__('Deal Quantity'),
			'align'     =>'left',
			'width'		=>'30px',
			'filter'    => false,
			'renderer'	=> new MGS_Deals_Block_Adminhtml_Deals_Renderer_Qty
		));

		$this->addColumn('sold', array(
			'header'    => Mage::helper('deals')->__('Sold'),
			'align'     =>'left',
			'index'     => 'sold',
			'width'		=> '30px',
		));


		$this->addColumn('status', array(
			'header'    => Mage::helper('deals')->__('Status'),
			'align'     => 'left',
			'width'     => '80px',
			'index'     => 'status',
			'type'      => 'options',
			'options'   => array(
				1 => 'Processing',
				2 => 'Running',
				3 => 'Done',
			),
			'renderer'	=> new MGS_Deals_Block_Adminhtml_Deals_Renderer_Status
		));
		
        return parent::_prepareColumns();
    }


    /**
     * Adds additional parameter to URL for loading only products grid
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('deals/adminhtml_widget/chooser', array(
            'products_grid' => true,
            '_current' => true,
            'uniq_id' => $this->getId(),
            'use_massaction' => $this->getUseMassaction(),
            'product_type_id' => $this->getProductTypeId(),
        ));
    }

    /**
     * Setter
     *
     * @param array $selectedProducts
     * @return Mage_Adminhtml_Block_Catalog_Product_Widget_Chooser
     */
    public function setSelectedProducts($selectedProducts)
    {
        $this->_selectedProducts = $selectedProducts;
        return $this;
    }

    /**
     * Getter
     *
     * @return array
     */
    public function getSelectedProducts()
    {
        if ($selectedProducts = $this->getRequest()->getParam('selected_products', null)) {
            $this->setSelectedProducts($selectedProducts);
        }
        return $this->_selectedProducts;
    }
}
		