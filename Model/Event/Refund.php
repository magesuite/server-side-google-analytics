<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model\Event;

class Refund
{
    /**
     * @var \MageSuite\ServerSideGoogleAnalytics\Model\Event\RefundBuilder
     */
    protected $refundBuilder;

    /**
     * @var \MageSuite\ServerSideGoogleAnalytics\Model\Client
     */
    protected $client;

    public function __construct(
        \MageSuite\ServerSideGoogleAnalytics\Model\Event\RefundBuilder $refundBuilder,
        \MageSuite\ServerSideGoogleAnalytics\Model\Client $client
    ) {
        $this->refundBuilder = $refundBuilder;
        $this->client = $client;
    }

    /**
     * @param \Magento\Sales\Model\Order $order
     */
    public function execute(\Magento\Sales\Model\Order $order): void
    {
        $eventData = $this->refundBuilder
            ->setOrder($order)
            ->create();
        $this->client->call(
            $eventData->toArray(),
            $order->getStoreId()
        );
    }
}
