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
        if (Mage::getStoreConfig('auction/general/fee_auction', $quote->getStoreId()) == 0) {
            return $this;
        }
        if (!$quote->isVirtual() && $address->getAddressType() == 'billing') {
            return $this;
        }

        if ($quote->isVirtual() && $address->getAddressType() == 'shipping') {
            return $this;
        }

        $exist_amount = $quote->getFeeAmount();

        $feeAuction = Magestore_Auction_Model_Feeauction::getFee();
        if($feeAuction <= 0)
            return $this;

        $store = Mage::app()->getStore();
        foreach ($address->getAllItems() as $item) {
            if ($item->getParentItemId())
                continue;
            if ($item->getHasChildren() && $item->isChildrenCalculated()) {
                foreach ($item->getChildren() as $child) {
                    if(!$child->isDeleted() && $item->getOptionByCode('bid_id') != NULL) {
                        $itemFeeTax = $feeAuction;
                        $baseTaxableAmount = $child->getBaseTaxableAmount();
                        $taxableAmount = $child->getTaxableAmount();
                        $child->setBaseTaxableAmount($baseTaxableAmount + $itemFeeTax);
                        $child->setTaxableAmount($taxableAmount + $itemFeeTax);

                        if(Mage::helper('tax')->priceIncludesTax()) {
                            $taxRate = $this->getItemRateOnQuote($address, $child->getProduct(), $store);
                            $baseHiddenTax = $this->round($this->calTax($baseTaxableAmount, $taxRate) - $this->calTax($child->getBaseTaxableAmount(), $taxRate));
                            $hiddenTax = $this->round($this->calTax($taxableAmount, $taxRate) - $this->calTax($child->getTaxableAmount(), $taxRate));

                            $this->_hiddentBaseDiscount += $baseHiddenTax;
                            $this->_hiddentDiscount += $hiddenTax;

                            $child->setBaseCustomercreditHiddenTax($child->getBaseCustomercreditHiddenTax() + $baseHiddenTax);
                            $child->setCustomercreditHiddenTax($child->getCustomercreditHiddenTax() + $hiddenTax);
                        }
                    }
                }
            } else if ($item->getProduct()) {
                if(!$item->isDeleted() && $item->getOptionByCode('bid_id') != NULL) {
                    $itemFeeTax = $feeAuction;

                    $baseTaxableAmount = $item->getBaseTaxableAmount();
                    $taxableAmount = $item->getTaxableAmount();
                    $item->setBaseTaxableAmount($baseTaxableAmount + $itemFeeTax);
                    $item->setTaxableAmount($taxableAmount + $itemFeeTax);

                    if(Mage::helper('tax')->priceIncludesTax()) {
                        $taxRate = $this->getItemRateOnQuote($address, $item->getProduct(), $store);
                        $baseHiddenTax = $this->round($this->calTax($baseTaxableAmount, $taxRate) - $this->calTax($item->getBaseTaxableAmount(), $taxRate));
                        $hiddenTax = $this->round($this->calTax($taxableAmount, $taxRate) - $this->calTax($item->getTaxableAmount(), $taxRate));

                        $this->_hiddentBaseDiscount += $baseHiddenTax;
                        $this->_hiddentDiscount += $hiddenTax;
                        $item->setBaseCustomercreditHiddenTax($item->getBaseCustomercreditHiddenTax() + $baseHiddenTax);
                        $item->setCustomercreditHiddenTax($item->getCustomercreditHiddenTax() + $hiddenTax);
                    }
                }
            }
        }

        //update address
        $address->setFeeAmount($feeAuction);
        $address->setGrandTotal($address->getGrandTotal() + $feeAuction + $this->_hiddentBaseDiscount);
        $address->setBaseGrandTotal($address->getBaseGrandTotal() + $feeAuction + $this->_hiddentDiscount);
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
        if (Mage::getStoreConfig('auction/general/fee_auction', $address->getStoreId()) == 1) {
            return $this;
        }
        if ($amount > 0) {
            $address->addTotal(array(
                'code' => $this->getCode(),
                'title' => Mage::helper('auction')->__('Fee Auction Product'),
                'value' => $amount
            ));
            return $this;
        }
    }
}