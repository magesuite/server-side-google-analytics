<?php
$resolver = \Magento\TestFramework\Workaround\Override\Fixture\Resolver::getInstance();
$resolver->requireDataFixture('Magento/Checkout/_files/quote_with_coupon_saved_rollback.php');
$resolver->requireDataFixture('Magento/Tax/_files/tax_rule_region_1_al_rollback.php');
$resolver->requireDataFixture('Magento/Catalog/_files/products_rollback.php');
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

$registry = $objectManager->get(\Magento\Framework\Registry::class);
$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

$order = $objectManager->get(\Magento\Sales\Model\OrderFactory::class)->create()->loadByIncrementId('test_order_1');
$orderRepository = $objectManager->create(\Magento\Sales\Api\OrderRepositoryInterface::class);
$orderRepository->delete($order);

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', false);
