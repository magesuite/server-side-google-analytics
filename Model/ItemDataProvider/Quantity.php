<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model\ItemDataProvider;

class Quantity implements \MageSuite\ServerSideGoogleAnalytics\Model\ItemDataProviderInterface
{
    public function getItemData(\Magento\Sales\Model\AbstractModel $item): array
    {
        $qty = $item->getQty();

        if ($item instanceof \Magento\Sales\Model\Order\Item) {
            $qty = $item->getQtyOrdered();
        }

        return ['quantity' => $qty * 1];
    }
}
