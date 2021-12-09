<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model\ProductDataProvider;

class DefaultParameters implements \MageSuite\ServerSideGoogleAnalytics\Model\ProductDataProviderInterface
{
    /**
     * @param \Magento\Sales\Model\Order\Item $orderItem
     * @param int $productIndex
     * @return array
     */
    public function getProductData(\Magento\Sales\Model\Order\Item $orderItem, int $productIndex): array
    {
        $parameterPrefix = 'pr' . $productIndex;
        $productData[$parameterPrefix . 'id'] = (string)$orderItem->getSku();
        $productData[$parameterPrefix . 'nm'] = (string)$orderItem->getName();
        $productData[$parameterPrefix . 'qt'] = (int)$orderItem->getQtyOrdered();

        return $productData;
    }
}
