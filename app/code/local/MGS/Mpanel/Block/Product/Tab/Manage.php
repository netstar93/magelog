<?php

class MGS_Mpanel_Block_Product_Tab_Manage extends Mage_Core_Block_Template {

    public function getProductTabs() {
        $tabs = array(
            'description',
            'additional',
            'reviews',
            'product_tag_list',
            'custom_tab_one',
            'custom_tab_two',
            'custom_tab_three',
            'product_questions'
        );
        return $tabs;
    }

}
