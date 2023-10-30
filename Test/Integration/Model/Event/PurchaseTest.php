<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Test\Integration\Model\Event;

class PurchaseTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\Sales\Model\OrderFactory $orderFactory;
    protected ?\MageSuite\ServerSideGoogleAnalytics\Model\Event\Purchase $purchaseEvent;
    protected ?\Magento\SalesRule\Model\RuleFactory $ruleFactory;
    protected ?\Magento\Framework\Registry $registry;

    protected function setUp(): void
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->orderFactory = $objectManager->get(\Magento\Sales\Model\OrderFactory::class);
        $this->purchaseEvent = $objectManager->get(\MageSuite\ServerSideGoogleAnalytics\Model\Event\Purchase::class);
        $this->ruleFactory = $objectManager->get(\Magento\SalesRule\Model\RuleFactory::class);
        $this->registry = $objectManager->get(\Magento\Framework\Registry::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture MageSuite_ServerSideGoogleAnalytics::Test/Integration/_files/order.php
     */
    public function testPurchaseEventData(): void
    {
        $ruleId = $this->registry->registry('Magento/Checkout/_file/discount_10percent');
        $couponCode = $this->ruleFactory->create()->load($ruleId)->getCouponCode();
        $order = $this->orderFactory->create()->loadByIncrementId('test_order_1');
        $eventData = $this->purchaseEvent->setOrder($order)->getData();
        $event = array_pop($eventData['events']);

        $this->assertEquals('dummy_id', $eventData['client_id']);
        $this->assertEquals('purchase', $event['name']);
        $this->assertEquals('USD', $event['params']['currency']);
        $this->assertEquals('test_order_1', $event['params']['transaction_id']);
        $this->assertEquals(88.05, $event['params']['value']);
        $this->assertEquals($couponCode, $event['params']['coupon']);
        $this->assertEquals(30.0, $event['params']['shipping']);
        $this->assertEquals(4.05, $event['params']['tax']);
        $this->assertEquals('dummy_id', $event['params']['session_id']);
        $this->assertEquals('simple', $event['params']['items'][0]['item_id']);
        $this->assertEquals('Simple Product', $event['params']['items'][0]['item_name']);
        $this->assertEquals('custom-design-simple-product', $event['params']['items'][1]['item_id']);
        $this->assertEquals('Custom Design Simple Product', $event['params']['items'][1]['item_name']);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/ConfigurableProduct/_files/order_item_with_configurable_and_options.php
     */
    public function testPurchaseEventDataForConfigurableProductItem(): void
    {
        $order = $this->orderFactory->create()->loadByIncrementId('100000001');
        $eventData = $this->purchaseEvent->setOrder($order)->getData();
        $event = array_pop($eventData['events']);

        $this->assertEquals('purchase', $event['name']);
        $this->assertEquals('100000001', $event['params']['transaction_id']);
        $this->assertEquals('simple_10', $event['params']['items'][0]['item_id']);
        $this->assertEquals('Configurable OptionOption 1', $event['params']['items'][0]['item_name']);
    }
}
