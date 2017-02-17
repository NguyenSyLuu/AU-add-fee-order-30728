<?php
$installer = $this;

$installer->startSetup();

$installer->run("

    ALTER TABLE {$this->getTable('auction_product')} 
        ADD `notification_email` varchar(255) NULL default '';

");
$installer->endSetup();