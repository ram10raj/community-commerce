<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Sundial\CommunityCommerce\Model\Total;


class Charity extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
   /**
     * Collect grand total address amount
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this
     */
    protected $quoteValidator = null; 

    public function __construct(\Magento\Quote\Model\QuoteValidator $quoteValidator,\Magento\Checkout\Model\Cart $cart)
    {
        $this->quoteValidator = $quoteValidator;
		$this->cart = $cart;
    }
  public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);


        //$exist_amount = 0; //$quote->getFee(); 
       // $charity = 100; //Excellence_Fee_Model_Fee::getFee();
		
        $balance = $this->getCharityAmount();
		if($quote->getCharityId()){
			$total->setTotalAmount('charity', $balance);
			$total->setBaseTotalAmount('charity', $balance);        
			$total->setCharity($balance);
			$total->setBaseCharity($balance);
			$total->setGrandTotal($total->getGrandTotal() + $balance);
			$total->setBaseGrandTotal($total->getBaseGrandTotal() + $balance);
			$quote->setCharityTotalAmount($balance);
			return $this;
		}else{
			$getCharity = $total->getCharity();
			$total->setGrandTotal($total->getGrandTotal() - $getCharity);
			$total->setBaseGrandTotal($total->getBaseGrandTotal() - $getCharity);			
			$total->setCharity(0);
			$total->setBaseCharity(0);
			$quote->setCharityTotalAmount(0);
			return $this;
		}
    } 

    
    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param Address\Total $total
     * @return array|null
     */
    /**
     * Assign subtotal amount and label to address object
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param Address\Total $total
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
			return [
				'code' => 'charity',
				'title' => 'Charity',
				'value' => $total->getCharity()
			];
    }

    /**
     * Get Subtotal label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __('Charity');
    }
	public function getCharityAmount(){
	$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$items = $this->cart->getQuote()->getAllItems();
		$totalAmount = 0;
		foreach($items as $item){
			$_product = $objectManager->get('Magento\Catalog\Model\Product')->load($item->getProductId());
			$charityPercentage = $_product->getResource()->getAttribute('charity_percentage')->getFrontend()->getValue($_product);
			$charityAvailable  = $_product->getResource()->getAttribute('charity_available')->getFrontend()->getValue($_product);
			if($charityAvailable == 'Yes' || $charityAvailable == 'yes'){
				$itemTotal = ($item->getRowTotal() * $charityPercentage)/100;
				$totalAmount += $itemTotal;
			}
		}
		return $totalAmount;
	}
}