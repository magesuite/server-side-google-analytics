<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Test\Integration\Model\Event;

class RefundBuilderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $order;

    /**
     * @var \MageSuite\ServerSideGoogleAnalytics\Model\Event\RefundBuilder
     */
    protected $refundBuilder;

    protected function setUp(): void
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->order = $objectManager->get(\Magento\Sales\Model\Order::class);
        $this->refundBuilder = $objectManager->get(\MageSuite\ServerSideGoogleAnalytics\Model\Event\RefundBuilder::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Sales/_files/order_with_two_simple_products.php
     */
    public function testRefundEventData(): void
    {
        $order = $this->order->loadByIncrementId('100000001');
        $order->setGaUserId('dummy_id');
        $eventData = $this->refundBuilder
            ->setOrder($order)
            ->create()
            ->toArray();
        $expectedArray = [
            't' => 'event',
            'ti' => '100000001',
            'ta' => $order->getStoreName(),
            'tr' => 0.0,
            'ts' => 0.0,
            'tt' => 0.0,
            'cu' => null,
            'pa' => 'refund',
            'pr1id' => 'simple',
            'pr1nm' => 'Simple Product',
            'pr1pr' => 10.0,
            'pr1qt' => 1,
            'pr2id' => 'simple-2',
            'pr2nm' => 'Simple Product 2',
            'pr2qt' => 1,
            'pr2pr' => 11.0,
            'cid' => 'dummy_id'
        ];

        $this->assertEquals($expectedArray, $eventData);
    }
}
