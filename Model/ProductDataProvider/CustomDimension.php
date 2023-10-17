<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model\ProductDataProvider;

class CustomDimension implements \MageSuite\ServerSideGoogleAnalytics\Model\ProductDataProviderInterface
{
    const MAX_PARAMETER_LENGTH = 150;

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
        $productData = [];
        $customDimensions = $this->configuration->getProductCustomDimension($orderItem->getStoreId());

        if (empty($customDimensions)) {
            return $productData;
        }

        foreach ($customDimensions as $row) {
            $attributeCode = (string)$row['product_attribute'];
            $attributeValue = $this->getAttributeValue($attributeCode, $orderItem);

            if ($attributeValue === null) {
                continue;
            }

            $dimensionIndex = (int)$row['dimension_index'];
            $parameter = sprintf('pr%dcd%d', $productIndex, $dimensionIndex);
            $productData[$parameter] = $attributeValue;
        }

        return $productData;
    }

    protected function getAttributeValue(string $attributeCode, \Magento\Sales\Model\Order\Item $orderItem): ?string
    {
        $product = $orderItem->getProduct();

        if ($product === null) {
            return null;
        }

        $value = $product->getData($attributeCode);

        if ($value === null) {
            return null;
        }

        $attribute = $product->getResource()->getAttribute($attributeCode);

        if ($attribute->usesSource()) {
            $value = $attribute->getSource()->getOptionText($value);
        }

        return strlen($value) > self::MAX_PARAMETER_LENGTH
            ? substr($string, 0, self::MAX_PARAMETER_LENGTH)
            : $value;
    }
}
