<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model\ItemDataProvider;

class General implements \MageSuite\ServerSideGoogleAnalytics\Model\ItemDataProviderInterface
{
    public function getItemData(\Magento\Sales\Model\AbstractModel $item): array
    {
        return [
            'item_id' => (string)$item->getSku(),
            'item_name' => (string)$item->getName(),
            'discount' => $item->getDiscountAmount()*1
        ];
    }
}
