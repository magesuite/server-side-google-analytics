<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model\ItemDataProvider;

class General implements \MageSuite\ServerSideGoogleAnalytics\Model\ItemDataProviderInterface
{
    public function getItemData(\Magento\Sales\Model\AbstractModel $item): array
    {
        return [
            'item_id' => $item->getProductOptionByCode('simple_sku') ?: $item->getSku(),
            'item_name' => $item->getProductOptionByCode('simple_name') ?: $item->getName(),
            'discount' => $item->getDiscountAmount()*1
        ];
    }
}
