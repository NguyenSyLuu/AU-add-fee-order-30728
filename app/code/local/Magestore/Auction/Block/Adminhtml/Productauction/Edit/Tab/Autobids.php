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

class Magestore_Auction_Block_Adminhtml_Productauction_Edit_Tab_Autobids extends Mage_Adminhtml_Block_Widget_Grid {

    /**
     * Magestore_Auction_Block_Adminhtml_Productauction_Edit_Tab_Autobids constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->setId('autobidsGrid');
        $this->setDefaultSort('autobid_id');
        $this->setUseAjax(true);
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * @return mixed
     */
    public function getAuctionId() {
        return $this->getRequest()->getParam('id');
    }

    /**
     * @return mixed
     */
    protected function _prepareCollection() {
        $timezone = ((Mage::app()->getLocale()->date()->get(Zend_Date::TIMEZONE_SECS)) / 3600);
        $collection = Mage::getResourceModel('auction/autobid_collection')
                ->addFieldToFilter('productauction_id', $this->getAuctionId());
        $collection->getSelect()
                ->columns(array(
                    'created_date_time' => new Zend_Db_Expr("SUBDATE(created_time, INTERVAL " . $timezone . " HOUR)"),
        ));
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return mixed
     */
    protected function _prepareColumns() {
        $store = $this->_getStore();

        $this->addColumn('autobid_id', array(
            'header' => Mage::helper('auction')->__('ID'),
            'width' => '50px',
            'index' => 'autobid_id',
            'type' => 'number',
        ));

        $this->addColumn('bidder_name', array(
            'header' => Mage::helper('auction')->__('Bidder Name'),
            'index' => 'bidder_name'
        ));

        $this->addColumn('customer_email', array(
            'header' => Mage::helper('auction')->__('Email'),
            'index' => 'customer_email',
            'renderer' => 'auction/adminhtml_productauction_renderer_customeremail',
        ));

        $this->addColumn('price', array(
            'header' => Mage::helper('auction')->__('Price'),
            'align' => 'left',
            'index' => 'price',
            'type' => 'price',
            'currency_code' => $store->getBaseCurrency()->getCode(),
        ));

        $this->addColumn('created_date_time', array(
            'header' => Mage::helper('auction')->__('Placed Time'),
            'align' => 'right',
            'index' => 'created_date_time',
            'type' => 'datetime',
            'width' => '180px',
            'filter_index' => 'created_time',
        ));

        return parent::_prepareColumns();
    }

    /**
     * @return mixed
     */
    public function getGridUrl() {
        return $this->getData('grid_url') ? $this->getData('grid_url') : $this->getUrl('*/*/autobidlist', array('_current' => true));
    }

    /**
     * @return mixed
     */
    protected function _getStore() {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

//    protected function _afterLoadCollection() {
//        if ($this->_isExport) {
//            $this->removeColumn('customer_email');
//            $this->removeColumn('price');
//            $this->removeColumn('created_date_time');
//            $this->addColumn('customer_email', array(
//                'header' => Mage::helper('auction')->__('Email'),
//                'index' => 'customer_email',
//            ));
//            $this->addColumn('price', array(
//                'header' => Mage::helper('auction')->__('price'),
//                'index' => 'price',  
//                'type'  => 'price',
//            ));
//            $this->addColumn('customer_email', array(
//                'header' => Mage::helper('auction')->__('Email'),
//                'index' => 'customer_email',
//            ));
//        }
//    }

}
