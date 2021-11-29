<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Test\Integration\Observer;

class SaveUserIdInOrderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $order;

    /**
     * @var \Magento\Sales\Api\OrderManagementInterface
     */
    protected $orderManagement;

    protected function setUp(): void
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->order = $objectManager->get(\Magento\Sales\Model\Order::class);
        $this->orderManagement = $objectManager->get(\Magento\Sales\Api\OrderManagementInterface::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Sales/_files/order.php
     * @magentoConfigFixture current_store server_side_google_analytics/general/enabled 1
     */
    public function testIfUserIdIsSavedIntoOrder(): void
    {
        $order = $this->order->loadByIncrementId('100000001');
        $this->orderManagement->place($order);
        $this->assertNotEmpty($order->getData('ga_user_id'));
    }
}
