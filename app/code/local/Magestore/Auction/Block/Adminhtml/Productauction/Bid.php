<?php
/**
 * Magestore
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_Auction
 * @module     Auction
 * @author      Magestore Developer
 *
 * @copyright   Copyright (c) 2016 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 *
 */

class Magestore_Auction_Block_Adminhtml_Productauction_Bid extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Magestore_Auction_Block_Adminhtml_Productauction_Bid constructor.
     */
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'auction';
        $this->_controller = 'adminhtml_auction';
        
        $this->_removeButton('save');
        $this->_removeButton('delete');
		$this->_removeButton('reset');
	}

    /**
     * @return mixed
     */
    public function getHeaderText()
    {
		return Mage::helper('auction')->__("Auction Bids for '%s'", $this->htmlEscape(Mage::registry('productauction_data')->getProductName()));
    }

    /**
     * @return mixed
     */
    public function getProductauction()
     { 
        if (!$this->hasData('productauction_data')) 
		{
            $this->setData('productauction_data', Mage::registry('productauction_data'));
        }
        return $this->getData('productauction_data');
    }
		
}