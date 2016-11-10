<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Sundial\CommunityCommerce\Observer;

use Magento\Framework\Event\ObserverInterface;

class SubmitCharity implements ObserverInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var OrderSender
     */

    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param OrderSender $orderSender
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var  \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getQuote();
        /** @var  \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getOrder();
        //save charity to order table
        $order->setCharityId($quote->getCharityId());
        $order->setCharityName($quote->getCharityName());
        $order->setCharityTotalAmount($quote->getCharityTotalAmount());
        $order->save();
        //save then charity details to shipping address
        $addressShipping = $order->getShippingAddress();
        $addressShipping->setCharity($quote->getShippingAddress()->getCharity());
        $addressShipping->setBaseCharity($quote->getShippingAddress()->getBaseCharity());
        $addressShipping->save();
        //save then charity details to Billing address
        $addressBilling = $order->getBillingAddress();
        $addressBilling->setCharity($quote->getBillingAddress()->getCharity());
        $addressBilling->setBaseCharity($quote->getBillingAddress()->getBaseCharity());
        $addressBilling->save();
    }
}
