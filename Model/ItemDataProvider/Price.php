<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model\ItemDataProvider;

class Price implements \MageSuite\ServerSideGoogleAnalytics\Model\ItemDataProviderInterface
{
    protected \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration;

    public function __construct(\MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getItemData(\Magento\Sales\Model\AbstractModel $item): array
    {
        $price = $this->configuration->excludeTaxFromCalculation($item->getStoreId())
            ? (float)$item->getRowTotal()
            : (float)$item->getRowTotalInclTax();

        return ['price' => $price];
    }
}
