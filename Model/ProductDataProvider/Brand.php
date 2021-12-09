<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model\ProductDataProvider;

class Brand implements \MageSuite\ServerSideGoogleAnalytics\Model\ProductDataProviderInterface
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
        $brand = $this->getProductBrand($orderItem);

        if (empty($productBrand)) {
            return [];
        }

        $parameter = sprintf('pr%dbr', $productIndex);
        $productData[$parameter] = (string)$brand;

        return $productData;
    }

    protected function getProductBrand(\Magento\Sales\Model\Order\Item $orderItem): ?string
    {
        $product = $orderItem->getProduct();
        $brandAttribute = $this->configuration->getBrandAttribute();

        if ($product === null || !$product->getData($brandAttribute)) {
            return null;
        }

        return (string)$product->getAttributeText($brandAttribute);
    }
}
