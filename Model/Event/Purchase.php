<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model\Event;

class Purchase implements EventInterface
{
    protected \MageSuite\ServerSideGoogleAnalytics\Model\ItemDataProviderInterface $itemDataProvider;
    protected \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration;
    protected ?\Magento\Sales\Api\Data\OrderInterface $order = null;

    public function __construct(
        \MageSuite\ServerSideGoogleAnalytics\Model\ItemDataProviderInterface $itemDataProvider,
        \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration
    ) {
        $this->itemDataProvider = $itemDataProvider;
        $this->configuration = $configuration;
    }

    public function getData(): array
    {
        $order = $this->getOrder();

        return [
            'client_id' => (string)$order->getGaClientId(),
            'events' => [
                [
                    'name' => 'purchase',
                    'params' => [
                        'currency' => (string)$order->getOrderCurrencyCode(),
                        'transaction_id' => (string)$order->getIncrementId(),
                        'value' => (float)$order->getGrandTotal(),
                        'coupon' => (string)$order->getCouponCode(),
                        'shipping' => $this->isPriceExcludingTax()
                            ? (float)$order->getShippingAmount()
                            : (float)$order->getShippingInclTax(),
                        'tax' => (float)$order->getTaxAmount(),
                        'session_id' => $order->getGaSessionId(),
                        'items' => $this->getItems()
                    ]
                ]
            ]
        ];
    }

    protected function getItems(): array
    {
        $items = [];
        $index = 0;

        /** @var \Magento\Sales\Model\Order\Item $orderItem */
        foreach ($this->getOrder()->getAllVisibleItems() as $orderItem) {
            $item = $this->itemDataProvider->getItemData($orderItem);
            $item['index'] = $index;
            $items[] = $item;
            $index++;
        }

        return $items;
    }

    public function getOrder(): \Magento\Sales\Api\Data\OrderInterface
    {
        if ($this->order instanceof \Magento\Sales\Api\Data\OrderInterface) {
            return $this->order;
        }

        throw new \Magento\Framework\Exception\LocalizedException(
            __('Unable to get order instance.')
        );
    }

    public function setOrder(\Magento\Sales\Api\Data\OrderInterface $order): self
    {
        $this->order = $order;
        return $this;
    }

    protected function isPriceExcludingTax(): bool
    {
        return $this->configuration->excludeTaxFromCalculation($this->getOrder()->getStoreId());
    }
}
