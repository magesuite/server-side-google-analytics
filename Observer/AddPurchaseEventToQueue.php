<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Observer;

class AddPurchaseEventToQueue implements \Magento\Framework\Event\ObserverInterface
{
    protected \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration;
    protected \MageSuite\ServerSideGoogleAnalytics\Model\Event\Purchase $purchaseEvent;
    protected \MageSuite\ServerSideGoogleAnalytics\Model\Queue\Publisher $queuePublisher;

    public function __construct(
        \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration,
        \MageSuite\ServerSideGoogleAnalytics\Model\Event\Purchase $purchaseEvent,
        \MageSuite\ServerSideGoogleAnalytics\Model\Queue\Publisher $queuePublisher
    ) {
        $this->configuration = $configuration;
        $this->purchaseEvent = $purchaseEvent;
        $this->queuePublisher = $queuePublisher;
    }

    public function execute(\Magento\Framework\Event\Observer $observer): void
    {
        $order = $observer->getOrder();

        if (!$this->configuration->isEnabled($order->getStoreId())
            || !$order->getData('ga_client_id')
            || !$order->getData('ga_session_id')) {
            return;
        }

        $eventData = $this->purchaseEvent->setOrder($order)->getData();
        $this->queuePublisher->publish($eventData, (int)$order->getStoreId());
    }
}
