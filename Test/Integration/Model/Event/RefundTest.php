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
        $order = $this->orderFactory->create()->loadByIncrementId('test_order_1');
        $creditMemo = $order->getCreditmemosCollection()->getFirstItem();
        $eventData = $this->refundEvent->setCreditMemo($creditMemo)->getData();
        $ruleId = $this->registry->registry('Magento/Checkout/_file/discount_10percent');
        $couponCode = $this->ruleFactory->create()->load($ruleId)->getCouponCode();
        $expectedArray = [
            'client_id' => 'dummy_id',
            'events' => [
                [
                    'name' => 'refund',
                    'params' => [
                        'currency' => 'USD',
                        'transaction_id' => 'test_order_1',
                        'value' => 88.05,
                        'coupon' => $couponCode,
                        'shipping' => 30.0,
                        'tax' => 4.05,
                        'items' => [
                            [
                                'item_id' => 'simple',
                                'item_name' => 'Simple Product',
                                'discount' => 4.0,
                                'price' => 40.0,
                                'quantity' => 4.0,
                                'index' => 0
                            ],
                            [
                                'item_id' => 'custom-design-simple-product',
                                'item_name' => 'Custom Design Simple Product',
                                'discount' => 2.0,
                                'price' => 20.0,
                                'quantity' => 2.0,
                                'index' => 1
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedArray, $eventData);
    }
}
