<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Observer;

class AddPurchaseEventToQueue implements \Magento\Framework\Event\ObserverInterface
{
    protected $handlerClass = \MageSuite\ServerSideGoogleAnalytics\Model\Queue\PurchaseEventHandler::class;

    /**
     * @var \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \MageSuite\Queue\Service\Publisher
     */
    protected $queuePublisher;

    public function __construct(
        \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration,
        \MageSuite\Queue\Service\Publisher $queuePublisher
    ) {
        $this->configuration = $configuration;
        $this->queuePublisher = $queuePublisher;
    }

    public function execute(\Magento\Framework\Event\Observer $observer): void
    {
        $order = $observer->getOrder();

        if (!$this->configuration->isEnabled($order->getStoreId())
            || !$order->getData('ga_user_id')) {
            return;
        }

        $this->queuePublisher->publish($this->handlerClass, $order->getId());
    }
}
