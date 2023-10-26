<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model\Event;

class RefundOrder extends Purchase
{
    public function getData(): array
    {
        $order = $this->getOrder();

        return [
            'client_id' => (string)$order->getGaClientId(),
            'events' => [
                [
                    'name' => 'refund',
                    'params' => [
                        'currency' => (string)$order->getOrderCurrencyCode(),
                        'transaction_id' => (string)$order->getIncrementId(),
                        'value' => (float)$order->getGrandTotal(),
                        'coupon' => (string)$order->getCouponCode(),
                        'shipping' => $this->isPriceExcludingTax()
                            ? (float)$order->getShippingAmount()
                            : (float)$order->getShippingInclTax(),
                        'tax' => (float)$order->getTaxAmount(),
                        'items' => $this->getItems()
                    ]
                ]
            ]
        ];
    }
}
