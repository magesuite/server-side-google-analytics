<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Test\Integration\Model\Event;

class RefundTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\Sales\Model\OrderFactory $orderFactory;
    protected ?\MageSuite\ServerSideGoogleAnalytics\Model\Event\Refund $refundEvent;
    protected ?\Magento\SalesRule\Model\RuleFactory $ruleFactory;
    protected ?\Magento\Framework\Registry $registry;

    protected function setUp(): void
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->orderFactory = $objectManager->get(\Magento\Sales\Model\OrderFactory::class);
        $this->refundEvent = $objectManager->get(\MageSuite\ServerSideGoogleAnalytics\Model\Event\Refund::class);
        $this->ruleFactory = $objectManager->get(\Magento\SalesRule\Model\RuleFactory::class);
        $this->registry = $objectManager->get(\Magento\Framework\Registry::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture MageSuite_ServerSideGoogleAnalytics::Test/Integration/_files/creditmemo.php
     */
    public function testRefundEventData(): void
    {
        $ruleId = $this->registry->registry('Magento/Checkout/_file/discount_10percent');
        $couponCode = $this->ruleFactory->create()->load($ruleId)->getCouponCode();
        $order = $this->orderFactory->create()->loadByIncrementId('test_order_1');
        $creditMemo = $order->getCreditmemosCollection()->getFirstItem();
        $eventData = $this->refundEvent->setCreditMemo($creditMemo)->getData();
        $event = array_pop($eventData['events']);

        $this->assertEquals('dummy_id', $eventData['client_id']);
        $this->assertEquals('refund', $event['name']);
        $this->assertEquals('USD', $event['params']['currency']);
        $this->assertEquals('test_order_1', $event['params']['transaction_id']);
        $this->assertEquals((float)$order->getGrandTotal(), $event['params']['value']);
        $this->assertEquals($couponCode, $event['params']['coupon']);
        $this->assertEquals(30.0, $event['params']['shipping']);
        $this->assertEquals((float)$order->getTaxAmount(), $event['params']['tax']);
        $this->assertEquals('simple', $event['params']['items'][0]['item_id']);
        $this->assertEquals('Simple Product', $event['params']['items'][0]['item_name']);
        $this->assertEquals('custom-design-simple-product', $event['params']['items'][1]['item_id']);
        $this->assertEquals('Custom Design Simple Product', $event['params']['items'][1]['item_name']);
    }
}
