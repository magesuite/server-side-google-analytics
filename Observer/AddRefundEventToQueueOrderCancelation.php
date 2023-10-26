<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Observer;

class AddRefundEventToQueueOrderCancelation implements \Magento\Framework\Event\ObserverInterface
{
    protected \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration;
    protected \MageSuite\ServerSideGoogleAnalytics\Model\Event\RefundOrder $refundEvent;
    protected \MageSuite\ServerSideGoogleAnalytics\Model\Queue\Publisher $queuePublisher;

    public function __construct(
        \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration,
        \MageSuite\ServerSideGoogleAnalytics\Model\Event\RefundOrder $refundEvent,
        \MageSuite\ServerSideGoogleAnalytics\Model\Queue\Publisher $queuePublisher
    ) {
        $this->configuration = $configuration;
        $this->refundEvent = $refundEvent;
        $this->queuePublisher = $queuePublisher;
    }

    public function execute(\Magento\Framework\Event\Observer $observer): void
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getOrder();

        if (!$this->configuration->isEnabled($order->getStoreId())
            || !$order->getData('ga_client_id')
            || !$order->getData('ga_session_id')) {
            return;
        }

        $eventData = $this->refundEvent->setOrder($order)->getData();
        $this->queuePublisher->publish($eventData, (int)$order->getStoreId());
    }
}
