<?php

class Magestore_Auction_Model_Feeauction extends Varien_Object
{

    /**
     * Retrieve Fee Amount
     *
     * @static
     * @return int
     */
    public static function getFee()
    {
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        $items = $quote->getAllItems();
        $i = 0;
        $feeAuction = 0;
        foreach ($items as $item) {
                $i++;
                $bidId = $item->getOptionByCode('bid_id');
//                Zend_Debug::dump($bidId->getValue());
                if ($bidId != null && $bidId->getValue() > 0) {
                    $bid = Mage::getModel('auction/auction')->load($bidId->getValue());
                    $auctionProduct = Mage::getModel('auction/productauction')->load($bid->getProductauctionId());
                    $priceAuction = $bid->getPrice();
                    $feePer = $auctionProduct->getFeeAuctionOrder();
                    $feeAuction = $priceAuction * $feePer / 100;
//                    echo Mage::helper('checkout')->getQuote()->getShippingAddress()->getData('tax_amount');
                    return $feeAuction;
//                    if(Mage::getStoreConfig('auction/general/fee_auction', $quote->getStoreId())){
//
//                    }else{
//
//                    }
                }
        }
            return $feeAuction;
    }
    /**
     * Check if fee can be apply
     *
     * @static
     * @param Mage_Sales_Model_Quote_Address $address
     * @return bool
     */
    public static function canApply($address)
    {
        // Put here your business logic to check if fee should be applied or not
        // Example of data retrieved :
        // $address->getShippingMethod(); > flatrate_flatrate
        // $address->getQuote()->getPayment()->getMethod(); > checkmo
        // $address->getCountryId(); > US
        // $address->getQuote()->getCouponCode(); > COUPONCODE
        return true;
    }
}