<?php
namespace Sundial\CommunityCommerce\Controller\Charity;
class Charity extends \Magento\Checkout\Controller\Cart
{ 
	protected $quoteRepository;
	protected $charityCollectionFactory;
	
	 public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
		\Sundial\CommunityCommerce\Model\ResourceModel\Charity\CollectionFactory $charityCollectionFactory
    ) {
        parent::__construct(
            $context,
            $scopeConfig,
            $checkoutSession,
            $storeManager,
            $formKeyValidator,
            $cart
        );
        $this->quoteRepository = $quoteRepository;
		$this->charityCollectionFactory = $charityCollectionFactory;
    }
	public function execute()
    {
        $charity = $this->getRequest()->getParam('remove') == 1
            ? ''
            : trim($this->getRequest()->getParam('charity_id'));
			//add charity
			$cartQuote = $this->cart->getQuote();
			
			//echo  $charity;
			if($charity){
				$escaper = $this->_objectManager->get('Magento\Framework\Escaper');
				$charityDesc = $this->_objectManager->get('Sundial\CommunityCommerce\Model\Charity')->load($charity);
				//print_r($charityDesc->getData());
				//exit;
				$cartQuote->setCharityId($charityDesc->getId());
				$cartQuote->setCharityName($charityDesc->getCharityName());
				$this->messageManager->addSuccess(__('Your charity amount is added to "%1".',
						$escaper->escapeHtml($charityDesc->getCharityName())));				
			}else{
			//remove charity
				$cartQuote->setCharityId('');
				$cartQuote->setCharityName('');
				$this->messageManager->addSuccess(__('You cancelled the charity.'));
			}
			try{
				$this->quoteRepository->save($cartQuote);
				$this->cart->save();
			}catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        }
		return $this->_goBack();
    }
}