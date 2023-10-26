<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model;

interface ItemDataProviderInterface
{
    public function getItemData(\Magento\Sales\Model\AbstractModel $item): array;
}
