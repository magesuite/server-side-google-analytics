<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model\Event;

class Refund implements EventInterface
{
    protected \MageSuite\ServerSideGoogleAnalytics\Model\ItemDataProviderInterface $itemDataProvider;
    protected \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration;
    protected ?\Magento\Sales\Api\Data\CreditmemoInterface $creditMemo = null;

    public function __construct(
        \MageSuite\ServerSideGoogleAnalytics\Model\ItemDataProviderInterface $itemDataProvider,
        \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration
    ) {
        $this->itemDataProvider = $itemDataProvider;
        $this->configuration = $configuration;
    }

    public function getData(): array
    {
        $creditMemo = $this->getCreditMemo();
        $order = $creditMemo->getOrder();

        return [
            'client_id' => (string)$order->getGaClientId(),
            'events' => [
                [
                    'name' => 'refund',
                    'params' => [
                        'currency' => (string)$creditMemo->getOrderCurrencyCode(),
                        'transaction_id' => (string)$order->getIncrementId(),
                        'value' => (float)$creditMemo->getGrandTotal(),
                        'coupon' => (string)$order->getCouponCode(),
                        'shipping' => $this->isPriceExcludingTax()
                            ? (float)$creditMemo->getShippingAmount()
                            : (float)$creditMemo->getShippingInclTax(),
                        'tax' => (float)$creditMemo->getTaxAmount(),
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

        /** @var \Magento\Sales\Model\Order\Creditmemo\Item $item */
        foreach ($this->getCreditMemo()->getAllItems() as $item) {
            $item = $this->itemDataProvider->getItemData($item);
            $item['index'] = $index;
            $items[] = $item;
            $index++;
        }

        return $items;
    }

    public function getCreditMemo(): \Magento\Sales\Api\Data\CreditmemoInterface
    {
        if ($this->creditMemo instanceof \Magento\Sales\Api\Data\CreditmemoInterface) {
            return $this->creditMemo;
        }

        throw new \Magento\Framework\Exception\LocalizedException(
            __('Unable to get credit memo instance.')
        );
    }

    public function setCreditMemo(\Magento\Sales\Api\Data\CreditmemoInterface $creditMemo): self
    {
        $this->creditMemo = $creditMemo;
        return $this;
    }

    protected function isPriceExcludingTax(): bool
    {
        return $this->configuration->excludeTaxFromCalculation($this->getCreditMemo()->getStoreId());
    }
}
