<?php

/* * ****************************************************
 * Package   : Brand
 * Author    : HIEPNH
 * Copyright : (c) 2015
 * ***************************************************** */
?>
<?php

$installer = $this;
$installer->startSetup();
$installer->run("
DROP TABLE IF EXISTS {$this->getTable('mgs_brand_store')};
CREATE TABLE {$this->getTable('mgs_brand_store')} (
  `brand_id` int(11) unsigned NOT NULL,
  `store_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`brand_id`,`store_id`),
  CONSTRAINT `FK_mgs_brand_store_store` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core/store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_mgs_brand_store_brand` FOREIGN KEY (`brand_id`) REFERENCES `{$this->getTable('mgs_brand')}` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");
$installer->endSetup();
