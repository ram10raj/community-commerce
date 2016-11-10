<?php
namespace Sundial\CommunityCommerce\Block\Charity;
class Charity extends \Magento\Framework\View\Element\Template
{
	 protected $charityFactory;
	 protected $cart;
	 
	 public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Sundial\CommunityCommerce\Model\ResourceModel\Charity\CollectionFactory $charityCollectionFactory,
		\Magento\Catalog\Model\Product\Attribute\Repository $productAttributeRepository,
		\Magento\Checkout\Model\Cart $cart
    ) {
		
        parent::__construct($context);
        $this->charityFactory = $charityCollectionFactory;
        $this->cart = $cart;
    }
	 
	 
	public function getCharityId(){
		$charityId = $this->cart->getQuote()->getCharityId();
		return $charityId;
	}
	public function getProductCharityOptions(){
		$items = $this->cart->getQuote()->getAllItems();
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		foreach($items as $item):			
			$_product = $objectManager->get('Magento\Catalog\Model\Product')->load($item->getProductId());
			$charityPercentage = $_product->getResource()->getAttribute('charity_percentage')->getFrontend()->getValue($_product);
			$charityAvailable  = $_product->getResource()->getAttribute('charity_available')->getFrontend()->getValue($_product);
			if($charityAvailable == 'Yes' || $charityAvailable == 'yes'){
				return 1;				
			}
		endforeach;
	}
	public function getAllCharity(){
		$charityCollection = $this->charityFactory->create()
								  ->addFieldToFilter('status', 1);
		$charityCollection->setOrder('sort_order','ASC');
		return $charityCollection;
	}
	public function getCharityDetails(){
		$charityId = $this->cart->getQuote()->getCharityId();
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$charityDetails = $objectManager->get('Sundial\CommunityCommerce\Model\Charity')->load($charityId);
		return $charityDetails;
	}
	
}
