<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Test\Integration\Model\ResourceModel;

class GetNumberOfOrdersTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \MageSuite\ServerSideGoogleAnalytics\Model\ResourceModel\GetNumberOfOrders
     */
    protected $getNumberOfOrders;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $orderRepository;

    protected function setUp(): void
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->getNumberOfOrders = $objectManager->get(\MageSuite\ServerSideGoogleAnalytics\Model\ResourceModel\GetNumberOfOrders::class);
        $this->searchCriteriaBuilder = $objectManager->get(\Magento\Framework\Api\SearchCriteriaBuilder::class);
        $this->orderRepository = $objectManager->get(\Magento\Sales\Api\OrderRepositoryInterface::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Sales/_files/two_orders_with_order_items.php
     */
    public function testIfReturnNumberOfOrders(): void
    {
        $order = $this->getOrder('100000001');
        $numberOfOrders = $this->getNumberOfOrders->execute(
            (string)$order->getCustomerEmail(),
            (int)$order->getId()
        );

        $this->assertEquals(1, $numberOfOrders);
    }

    protected function getOrder(string $incrementId)
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('increment_id', $incrementId)
            ->create();

        $items = $this->orderRepository
            ->getList($searchCriteria)
            ->getItems();

        return array_pop($items);
    }
}
