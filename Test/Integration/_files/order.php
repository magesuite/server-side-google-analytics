<?php
$resolver = \Magento\TestFramework\Workaround\Override\Fixture\Resolver::getInstance();
$resolver->requireDataFixture('Magento/Catalog/_files/products.php');
$resolver->requireDataFixture('Magento/Tax/_files/tax_rule_region_1_al.php');
$resolver->requireDataFixture('Magento/Checkout/_files/quote_with_coupon_saved.php');
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$getQuoteByReservedOrderId = $objectManager->get(\Magento\TestFramework\Quote\Model\GetQuoteByReservedOrderId::class);
$productRepository = $objectManager->create(\Magento\Catalog\Api\ProductRepositoryInterface::class);
$quote = $getQuoteByReservedOrderId->execute('test_order_1');

foreach (['simple', 'custom-design-simple-product'] as $sku) {
    /** @var $product \Magento\Catalog\Model\Product */
    $product = $productRepository->get($sku);
    $requestInfo = new \Magento\Framework\DataObject(['qty' => 2]);
    /** @var $cart \Magento\Checkout\Model\Cart */
    $quote->addProduct($product, $requestInfo);
    $quote->save();
}

$quote->getPayment()->setMethod('checkmo');
$quote->getShippingAddress()
    ->setShippingMethod('flatrate_flatrate')
    ->setCollectShippingRates(true);
$quote->collectTotals()->save();
$cartManagement = $objectManager->get(\Magento\Quote\Api\CartManagementInterface::class);
$orderId = $cartManagement->placeOrder($quote->getId());
/** @var \Magento\Sales\Api\OrderRepositoryInterface $orderRepository */
$orderRepository = $objectManager->get(\Magento\Sales\Api\OrderRepositoryInterface::class);
/** @var \Magento\Sales\Model\Order $order */
$order = $orderRepository->get($orderId);
$order->setGaClientId('dummy_id');
$order->setGaSessionId('dummy_id');
$order->setRemoteIp('127.0.0.1');
$orderRepository->save($order);
