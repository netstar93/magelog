<?php

class MGS_Profiles_Block_Adminhtml_Profile_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId("profileGrid");
        $this->setDefaultSort("profile_id");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel("profiles/profile")->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn("profile_id", array(
            "header" => Mage::helper("profiles")->__("ID"),
            "align" => "right",
            "width" => "50px",
            "type" => "number",
            "index" => "profile_id",
        ));

        $this->addColumn("name", array(
            "header" => Mage::helper("profiles")->__("Name"),
            "index" => "name",
        ));
        $this->addColumn("position", array(
            "header" => Mage::helper("profiles")->__("Position"),
            "index" => "position",
        ));
        $this->addColumn("address", array(
            "header" => Mage::helper("profiles")->__("Address"),
            "index" => "address",
        ));
        $this->addColumn("email", array(
            "header" => Mage::helper("profiles")->__("Email"),
            "index" => "email",
        ));
        $this->addColumn("facebook", array(
            "header" => Mage::helper("profiles")->__("Facebook"),
            "index" => "facebook",
        ));
        $this->addColumn("twitter", array(
            "header" => Mage::helper("profiles")->__("Twitter"),
            "index" => "twitter",
        ));
        $this->addColumn("linkedin", array(
            "header" => Mage::helper("profiles")->__("Linkedin"),
            "index" => "linkedin",
        ));
        $this->addColumn("instagram", array(
            "header" => Mage::helper("profiles")->__("Instagram"),
            "index" => "instagram",
        ));
        $this->addColumn("google_plus", array(
            "header" => Mage::helper("profiles")->__("Google Plus"),
            "index" => "google_plus",
        ));
        $this->addColumn('status', array(
            'header' => Mage::helper('profiles')->__('Status'),
            'index' => 'status',
            'type' => 'options',
            'options' => MGS_Profiles_Block_Adminhtml_Profile_Grid::getOptionArray12(),
        ));

        $this->addRssList('profiles/adminhtml_rss_rss/profile', Mage::helper('profiles')->__('RSS'));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        return $this->getUrl("*/*/edit", array("id" => $row->getId()));
    }

    static public function getOptionArray12() {
        $data_array = array();
        $data_array[0] = 'Enable';
        $data_array[1] = 'Disable';
        return($data_array);
    }

    static public function getValueArray12() {
        $data_array = array();
        foreach (MGS_Profiles_Block_Adminhtml_Profile_Grid::getOptionArray12() as $k => $v) {
            $data_array[] = array('value' => $k, 'label' => $v);
        }
        return($data_array);
    }

}
