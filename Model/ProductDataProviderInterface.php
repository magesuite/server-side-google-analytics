<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model;

interface ProductDataProviderInterface
{
    /**
     * @param \Magento\Sales\Model\Order\Item $orderItem
     * @param int $productIndex
     * @return array
     */
    public function getProductData(\Magento\Sales\Model\Order\Item $orderItem, int $productIndex): array;
}
