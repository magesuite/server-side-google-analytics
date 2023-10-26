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
        $order = $this->orderFactory->create()->loadByIncrementId('test_order_1');
        $eventData = $this->purchaseEvent->setOrder($order)->getData();
        $ruleId = $this->registry->registry('Magento/Checkout/_file/discount_10percent');
        $couponCode = $this->ruleFactory->create()->load($ruleId)->getCouponCode();
        $expectedArray = [
            'client_id' => 'dummy_id',
            'page_location' => 'http://localhost/index.php/checkout/onepage/success/',
            'events' => [
                [
                    'name' => 'purchase',
                    'params' => [
                        'currency' => 'USD',
                        'transaction_id' => 'test_order_1',
                        'value' => 88.05,
                        'coupon' => $couponCode,
                        'shipping' => 30.0,
                        'tax' => 4.05,
                        'session_id' => 'dummy_id',
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
                                'quantity' => 2,
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
