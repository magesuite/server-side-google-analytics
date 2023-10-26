<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model;

class CompositeItemDataProvider implements ItemDataProviderInterface
{
    /**
     * @var ItemDataProviderInterface[]
     */
    protected array $itemDataProviders;

    /**
     * @param ItemDataProviderInterface[] $itemDataProviders
     */
    public function __construct(array $itemDataProviders = [])
    {
        $this->itemDataProviders = $itemDataProviders;
    }

    public function getItemData(\Magento\Sales\Model\AbstractModel $item): array
    {
        $itemData = [];

        foreach ($this->itemDataProviders as $itemDataProvider) {
            $itemData = array_merge_recursive(
                $itemData,
                $itemDataProvider->getItemData($item)
            );
        }

        return $itemData;
    }
}
