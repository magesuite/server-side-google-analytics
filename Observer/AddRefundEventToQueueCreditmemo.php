<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Observer;

class AddRefundEventToQueueCreditmemo implements \Magento\Framework\Event\ObserverInterface
{
    protected \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration;
    protected \MageSuite\ServerSideGoogleAnalytics\Model\Event\Refund $refundEvent;
    protected \MageSuite\ServerSideGoogleAnalytics\Model\Queue\Publisher $queuePublisher;

    public function __construct(
        \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration,
        \MageSuite\ServerSideGoogleAnalytics\Model\Event\Refund $refundEvent,
        \MageSuite\ServerSideGoogleAnalytics\Model\Queue\Publisher $queuePublisher
    ) {
        $this->configuration = $configuration;
        $this->refundEvent = $refundEvent;
        $this->queuePublisher = $queuePublisher;
    }

    public function execute(\Magento\Framework\Event\Observer $observer): void
    {
        /** @var \Magento\Sales\Model\Order\Creditmemo $creditmemo */
        $creditmemo = $observer->getCreditmemo();
        $order = $creditmemo->getOrder();

        if (!$this->configuration->isEnabled($order->getStoreId())
            || !$order->getData('ga_client_id')
            || !$order->getData('ga_session_id')) {
            return;
        }

        $eventData = $this->refundEvent->setCreditMemo($creditmemo)->getData();
        $this->queuePublisher->publish($eventData, (int)$order->getStoreId());
    }
}
