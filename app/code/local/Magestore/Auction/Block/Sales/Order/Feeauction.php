<?php
/**
 * Created by Magestore
 *
 * @category   Magestore
 * @package    Magestore_Auction
 * @author     Leric (http://www.magestore.com)
 */
class Magestore_Auction_Block_Sales_Order_Feeauction extends Mage_Core_Block_Template
{
    /**
     * Get order store object
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        return $this->getParentBlock()->getOrder();
    }
    /**
     * Get totals source object
     *
     * @return Mage_Sales_Model_Order
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }
    /**
     * Initialize fee totals
     *
     * @return Magestore_Auction_Block_Sales_Order_Feeauction
     */
    public function initTotals()
    {
//        if ((float) $this->getOrder()->getBaseFeeAmount()) {
            $source = $this->getSource();
            $value  = $source->getFeeAmount();
            $this->getParentBlock()->addTotal(new Varien_Object(array(
                'code'   => 'fee',
                'strong' => false,
                'label'  => Mage::helper('auction')->__('Fee Auction bl-sa-or'),
                'value'  => $value
            )));
//        }
        return $this;
    }
}