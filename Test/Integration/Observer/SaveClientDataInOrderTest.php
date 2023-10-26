<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Test\Integration\Observer;

class SaveClientDataInOrderTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\Sales\Model\OrderFactory $orderFactory;
    protected ?\Magento\Sales\Api\OrderManagementInterface $orderManagement;
    protected ?\Magento\Framework\Stdlib\CookieManagerInterface $cookieManager;

    protected function setUp(): void
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->orderFactory = $objectManager->get(\Magento\Sales\Model\OrderFactory::class);
        $this->orderManagement = $objectManager->get(\Magento\Sales\Api\OrderManagementInterface::class);
        $this->cookieManager = $objectManager->get(\Magento\Framework\Stdlib\CookieManagerInterface::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Sales/_files/order.php
     * @magentoConfigFixture current_store server_side_google_analytics/general/enabled 1
     * @magentoConfigFixture current_store server_side_google_analytics/general/measurement_id G-XXXXXXXXXX
     */
    public function testIfUserIdIsSavedIntoOrder(): void
    {
        $order = $this->orderFactory->create()->loadByIncrementId('100000001');
        $this->cookieManager->setPublicCookie('_ga', 'GA1.1.1086824108.1697540205');
        $this->cookieManager->setPublicCookie('_ga_XXXXXXXXXX', 'GS1.1.1697540204.1.1.1697540214.50.0.0');
        $this->orderManagement->place($order);

        $this->assertEquals('1086824108.1697540205', $order->getData('ga_client_id'));
        $this->assertNotEmpty('1697540204', $order->getData('ga_session_id'));
    }
}
