<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model\ResourceModel;

class GetNumberOfOrders
{
    protected \Magento\Framework\App\ResourceConnection $resourceConnection;

    public function __construct(\Magento\Framework\App\ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
    }

    public function execute(string $customerEmail, int $orderEntityId): int
    {
        $connection = $this->resourceConnection->getConnection();
        $select = $connection->select()
            ->from($connection->getTableName('sales_order'), 'COUNT(*)')
            ->where('customer_email = ?', $customerEmail)
            ->where('entity_id != ?', $orderEntityId)
            ->where('state <> ?', \Magento\Sales\Model\Order::STATE_CANCELED);

        return (int)$connection->fetchOne($select);
    }
}
