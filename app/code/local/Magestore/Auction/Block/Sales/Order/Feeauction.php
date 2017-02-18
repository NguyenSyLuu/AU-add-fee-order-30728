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
//        Zend_Debug::dump($this->getOrder()->debug());
//        $_items = $this->getOrder()->getItemsCollection();
//
//        foreach ($_items as $item) {
//            if ($item->getParentItem()) continue;
//            //do something
//            $bidId = $item->getOptionByCode('bid_id');
//            Zend_Debug::dump($bidId->getValue());
//        }
//        if ((float) $this->getOrder()->getBaseFeeAmount()) {
            $source = $this->getSource();
            $value  = $source->getFeeAmount();
            $this->getParentBlock()->addTotal(new Varien_Object(array(
                'code'   => 'fee',
                'strong' => false,
                'label'  => Mage::helper('auction')->__('Fee Auction Product'),
                'value'  => $value
            )));
//        }
        return $this;
    }
}