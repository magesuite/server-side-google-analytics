<?php
$resolver = \Magento\TestFramework\Workaround\Override\Fixture\Resolver::getInstance();
$resolver->requireDataFixture('MageSuite_ServerSideGoogleAnalytics::Test/Integration/_files/order.php');
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$invoiceService = $objectManager->get(\Magento\Sales\Model\Service\InvoiceService::class);
$creditMemoFactory = $objectManager->get(\Magento\Sales\Model\Order\CreditmemoFactory::class);
$creditMemoService = $objectManager->get(\Magento\Sales\Model\Service\CreditmemoService::class);
$order = $objectManager->get(\Magento\Sales\Model\OrderFactory::class)->create()->loadByIncrementId('test_order_1');
$invoice = $invoiceService->prepareInvoice($order);
$invoice->register();
$invoice->save();
$creditmemo = $creditMemoFactory->createByOrder($order);
$creditmemo->save();
