<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model\Event;

abstract class AbstractBuilder
{
    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $order;

    /**
     * @var \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration
     */
    protected $configuration;

    public function __construct(\MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function setOrder(\Magento\Sales\Model\Order $order): self
    {
        $this->order = $order;
        return $this;
    }

    public function getOrder(): \Magento\Sales\Model\Order
    {
        if ($this->order instanceof \Magento\Sales\Model\Order) {
            return $this->order;
        }

        throw new \Magento\Framework\Exception\LocalizedException(
            __('Unable to get order instance.')
        );
    }

    protected function getEventData(): \Magento\Framework\DataObject
    {
        $order = $this->getOrder();
        $eventData = [];
        $eventData['t'] = 'event';
        $eventData['uid'] = (string)$order->getData('ga_user_id');
        $eventData['ti'] = $order->getIncrementId();
        $eventData['ta'] = $order->getStoreName();
        $eventData['tr'] = (float)$order->getGrandTotal();
        $eventData['ts'] = $this->isPriceExcludingTax() ? (float)$order->getShippingAmount() : (float)$order->getShippingInclTax();
        $eventData['tt'] = (float)$order->getTaxAmount();
        $eventData['cu'] = $order->getOrderCurrencyCode();
        $eventData['pa'] = $this->getProductAction();

        if ($order->getCouponCode()) {
            $eventData['tcc'] = $order->getCouponCode();
        }

        $eventData = array_merge($eventData, $this->getProducts());
        $eventDataObject = new \Magento\Framework\DataObject($eventData);

        return $eventDataObject;
    }

    protected function getProducts(): array
    {
        $productData = [];
        $productIndex = 1;

        /** @var \Magento\Sales\Model\Order\Item $orderItem */
        foreach ($this->getOrder()->getAllVisibleItems() as $orderItem) {
            $productKey = 'pr' . $productIndex;
            $productData[$productKey . 'id'] = (string)$orderItem->getSku();
            $productData[$productKey . 'nm'] = (string)$orderItem->getName();
            $productData[$productKey . 'pr'] = $this->isPriceExcludingTax()
                ? (float)$orderItem->getPrice() : (float)$orderItem->getPriceInclTax();
            $productData[$productKey . 'qt'] = (int)$orderItem->getQtyOrdered();
            $productIndex++;
        }

        return $productData;
    }

    protected function isPriceExcludingTax(): bool
    {
        return $this->configuration->excludeTaxFromCalculation($this->getOrder()->getStoreId());
    }

    abstract protected function getProductAction();

    abstract public function create();
}
