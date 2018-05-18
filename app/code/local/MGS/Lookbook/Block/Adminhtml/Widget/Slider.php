<?php

class MGS_Lookbook_Block_Adminhtml_Widget_Slider extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Block construction, prepare grid params
     *
     * @param array $arguments Object data
     */
    public function __construct($arguments=array())
    {
        parent::__construct($arguments);
        $this->setDefaultSort('slide_id');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        $this->setDefaultFilter(array('chooser_is_active' => '1'));
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
        $sourceUrl = $this->getUrl('*/lookbook_slide/chooserslider', array('uniq_id' => $uniqId));

        $chooser = $this->getLayout()->createBlock('widget/adminhtml_widget_chooser')
            ->setElement($element)
            ->setTranslationHelper($this->getTranslationHelper())
            ->setConfig($this->getConfig())
            ->setFieldsetId($this->getFieldsetId())
            ->setSourceUrl($sourceUrl)
            ->setUniqId($uniqId);


        if ($element->getValue()) {
            $slider = Mage::getModel('lookbook/slide')->load($element->getValue());
            if ($slider->getId()) {
                $chooser->setLabel($slider->getTitle());
            }
        }

        $element->setData('after_element_html', $chooser->toHtml());
        return $element;
    }

    /**
     * Grid Row JS Callback
     *
     * @return string
     */
    public function getRowClickCallback()
    {
        $chooserJsObject = $this->getId();
        $js = '
            function (grid, event) {
                var trElement = Event.findElement(event, "tr");
                var lookbookSliderId = trElement.down("td").innerHTML.replace(/^\s+|\s+$/g,"");
                var lookbookSliderName = trElement.down("td").next().innerHTML;
                '.$chooserJsObject.'.setElementValue(lookbookSliderId);
                '.$chooserJsObject.'.setElementLabel(lookbookSliderName);
                '.$chooserJsObject.'.close();
            }
        ';
        return $js;
    }

    /**
     * Prepare Cms static blocks collection
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('lookbook/slide')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare columns for Cms blocks grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('chooser_id', array(
            'header'    => Mage::helper('lookbook')->__('ID'),
            'align'     => 'right',
            'index'     => 'slide_id',
            'width'     => 50
        ));

        $this->addColumn('chooser_title', array(
            'header'    => Mage::helper('lookbook')->__('Slider Name'),
            'align'     => 'left',
            'index'     => 'title',
        ));


        $this->addColumn('chooser_is_active', array(
            'header'    => Mage::helper('lookbook')->__('Status'),
            'index'     => 'status',
            'type'      => 'options',
            'options'   => array(
                1 => Mage::helper('lookbook')->__('Enabled'),
                2 => Mage::helper('lookbook')->__('Disabled')
            ),
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/lookbook_slide/chooserslider', array('_current' => true));
    }
}
