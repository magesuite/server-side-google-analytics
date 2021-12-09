<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model\ProductDataProvider;

class Price implements \MageSuite\ServerSideGoogleAnalytics\Model\ProductDataProviderInterface
{
    /**
     * @var \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration
     */
    protected $configuration;

    public function __construct(\MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param \Magento\Sales\Model\Order\Item $orderItem
     * @param int $productIndex
     * @return array
     */
    public function getProductData(\Magento\Sales\Model\Order\Item $orderItem, int $productIndex): array
    {
        $price = $this->configuration->excludeTaxFromCalculation($orderItem->getStoreId())
            ? (float)$orderItem->getPrice()
            : (float)$orderItem->getPriceInclTax();
        $parameter = sprintf('pr%dpr', $productIndex);
        $productData[$parameter] = $price;

        return $productData;
    }
}
