<?php
$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE  ".$this->getTable('auction_product')." ADD `fee_auction_order` int NOT NULL default '0';
 ");
$installer->endSetup(); 