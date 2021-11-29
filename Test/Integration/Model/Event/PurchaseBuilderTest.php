<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Test\Integration\Model\Event;

class PurchaseBuilderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $order;

    /**
     * @var \MageSuite\ServerSideGoogleAnalytics\Model\Event\PurchaseBuilder
     */
    protected $purchaseBuilder;

    protected function setUp(): void
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->order = $objectManager->get(\Magento\Sales\Model\Order::class);
        $this->purchaseBuilder = $objectManager->get(\MageSuite\ServerSideGoogleAnalytics\Model\Event\PurchaseBuilder::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Sales/_files/order.php
     */
    public function testPurchaseEventData(): void
    {
        $order = $this->order->loadByIncrementId('100000001');
        $order->setGaUserId('dummy_id');
        $eventData = $this->purchaseBuilder
            ->setOrder($order)
            ->create()
            ->toArray();
        $expectedArray = [
            't' => 'event',
            'uid' => 'dummy_id',
            'uip' => '',
            'dp' => '/checkout/onepage/success/',
            'ti' => '100000001',
            'ta' => $order->getStoreName(),
            'tr' => 100.0,
            'ts' => '0.0000',
            'tt' => 0.0,
            'cu' => 'USD',
            'pa' => 'purchase',
            'pr1id' => 'simple',
            'pr1nm' => 'Simple Product',
            'pr1pr' => 10.0,
            'pr1qt' => 2
        ];
        $this->assertEquals($expectedArray, $eventData);
    }
}
