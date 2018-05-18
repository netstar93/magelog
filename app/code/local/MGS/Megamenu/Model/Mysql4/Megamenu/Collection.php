<?php

class MGS_Megamenu_Model_Mysql4_Megamenu_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('megamenu/megamenu');
    }
	
	public function addStoreFilter($store, $adminStore = true) {
		$stores = array();
        if ($store instanceof Mage_Core_Model_Store) {
            $stores[] = (int)$store->getId();
        }
		$stores[] = 0;
		$storeTable = Mage::getSingleton('core/resource')->getTableName('mgs_megamenu_store');
        $this->getSelect()->join(
                        array('stores' => $storeTable),
                        'main_table.megamenu_id = stores.megamenu_id',
                        array()
                )
                ->where('stores.store_id in (?)', ($adminStore ? $stores : $store));
        return $this;
    }
    public function addNowFilter() {
        $now = Mage::getSingleton('core/date')->gmtDate();
        $where = "from_date < '" . $now . "' AND ((to_date > '" . $now . "') OR (to_date IS NULL))";
        $this->getSelect()->where($where);
		return $this;
    }
}