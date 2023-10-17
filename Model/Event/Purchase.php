<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model\Event;

class Purchase
{
    /**
     * @var \MageSuite\ServerSideGoogleAnalytics\Model\Event\PurchaseBuilder
     */
    protected $purchaseBuilder;

    /**
     * @var \MageSuite\ServerSideGoogleAnalytics\Model\Client
     */
    protected $client;

    public function __construct(
        \MageSuite\ServerSideGoogleAnalytics\Model\Event\PurchaseBuilder $purchaseBuilder,
        \MageSuite\ServerSideGoogleAnalytics\Model\Client $client
    ) {
        $this->purchaseBuilder = $purchaseBuilder;
        $this->client = $client;
    }

    /**
     * @param \Magento\Sales\Model\Order $order
     */
    public function execute(\Magento\Sales\Model\Order $order): void
    {
        $eventData = $this->purchaseBuilder
            ->setOrder($order)
            ->create();

        $this->client->call(
            $eventData->toArray(),
            $order->getStoreId()
        );
    }
}
