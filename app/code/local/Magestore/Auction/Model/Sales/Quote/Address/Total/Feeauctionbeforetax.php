<?php

class Magestore_Auction_Model_Sales_Quote_Address_Total_Feeauctionbeforetax extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    protected $_code = 'fee';

    /**
     * Collect fee address amount
     *
     * @param Mage_Sales_Model_Quote_Address $address
     * @return Magestore_Auction_Model_Sales_Quote_Address_Total_Feeauctionbeforetax
     */
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        parent::collect($address);
        $this->_setAmount(0);
        $this->_setBaseAmount(0);
        $items = $this->_getAddressItems($address);
        if (!count($items)) {
            return $this;
        }
        $quote = $address->getQuote();
        $items = $quote->getAllItems();
        foreach ($items as $item) {
            $bidId = $item->getOptionByCode('bid_id');
            if ($bidId != NULL) {
                continue;
            }
        }

        if (Mage::getStoreConfig('auction/general/fee_auction', $quote->getStoreId()) == 1) {
            return $this;
        }
        if (!$quote->isVirtual() && $address->getAddressType() == 'billing') {
            return $this;
        }
        if ($quote->isVirtual() && $address->getAddressType() == 'shipping') {
            return $this;
        }
        if (Magestore_Auction_Model_Feeauction::canApply($address)) {
            $exist_amount = $quote->getFeeAmount();
            $fee = Magestore_Auction_Model_Feeauction::getFee();
            $balance = $fee - $exist_amount;
            $address->setFeeAmount($balance);
            $address->setBaseFeeAmount($balance);
            $quote->setFeeAmount($balance);
            $tax_amount = $quote->getShippingAddress()->getData('tax_amount');
            $address->setGrandTotal($address->getGrandTotal() + $address->getFeeAmount() + $tax_amount);
            $address->setBaseGrandTotal($address->getBaseGrandTotal() + $address->getBaseFeeAmount() + $tax_amount);
//            Zend_Debug::dump($address->debug());
            $address->save();
        }
//        Zend_Debug::dump($address->debug());die();
        return $this;
    }

    /**
     * Add fee information to address
     *
     * @param Mage_Sales_Model_Quote_Address $address
     * @return Magestore_Auction_Model_Sales_Quote_Address_Total_Feeauctionbeforetax
     */
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        $amount = $address->getFeeAmount();
//        Zend_Debug::dump($address->debug());
        if (Mage::getStoreConfig('auction/general/fee_auction', $address->getStoreId()) == 1) {
            return $this;
        }
        if ($amount > 0) {
            $address->addTotal(array(
                'code' => $this->getCode(),
                'title' => Mage::helper('auction')->__('Fee Auction Product q-a before tax'),
                'value' => $amount
            ));
            return $this;
        }
    }
}