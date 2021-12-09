<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model;

class CompositeProductDataProvider implements ProductDataProviderInterface
{
    /**
     * @var ProductDataProviderInterface[]
     */
    protected $productDataProviders;

    /**
     * @param ProductDataProviderInterface[] $productDataProviders
     */
    public function __construct(array $productDataProviders = [])
    {
        $this->productDataProviders = $productDataProviders;
    }

    /**
     * @param \Magento\Sales\Model\Order\Item $orderItem
     * @param int $productIndex
     * @return array
     */
    public function getProductData(\Magento\Sales\Model\Order\Item $orderItem, int $productIndex): array
    {
        $productData = [];

        foreach ($this->productDataProviders as $productDataProvider) {
            $productData = array_merge_recursive(
                $productData,
                $productDataProvider->getProductData($orderItem, $productIndex)
            );
        }

        return $productData;
    }
}
