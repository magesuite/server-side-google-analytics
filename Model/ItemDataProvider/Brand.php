<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model\ItemDataProvider;

class Brand implements \MageSuite\ServerSideGoogleAnalytics\Model\ItemDataProviderInterface
{
    public function getItemData(\Magento\Sales\Model\AbstractModel $item): array
    {
        $brand = $this->getProductBrand($item);

        if (empty($productBrand)) {
            return [];
        }

        return ['brand' => $brand];
    }

    protected function getProductBrand(\Magento\Sales\Model\AbstractModel $item): ?string
    {
        $product = $item->getProduct();

        if ($product === null || !$product->getData('brand')) {
            return null;
        }

        return (string)$product->getAttributeText('brand');
    }
}
