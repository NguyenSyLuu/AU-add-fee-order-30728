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

class Magestore_Auction_Block_Adminhtml_Transaction_Renderer_Order
	extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	/* Render Grid Column*/
    /**
     * @param Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row)
	{
		return sprintf('
			<a href="%s" title="%s">%s</a>',
			$this->getUrl('adminhtml/sales_order/view/', array('_current'=>true, 'order_id' => $row->getOrderId())),
			Mage::helper('catalog')->__('View Order Detail'),
			$row->getOrderNumber()
		);
	}
}