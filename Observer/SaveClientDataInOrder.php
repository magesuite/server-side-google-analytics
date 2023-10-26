<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Observer;

class SaveClientDataInOrder implements \Magento\Framework\Event\ObserverInterface
{
    protected \MageSuite\ServerSideGoogleAnalytics\Model\SessionProvider $sessionProvider;
    protected \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration;

    public function __construct(
        \MageSuite\ServerSideGoogleAnalytics\Model\SessionProvider $sessionProvider,
        \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration
    ) {
        $this->sessionProvider = $sessionProvider;
        $this->configuration = $configuration;
    }

    public function execute(\Magento\Framework\Event\Observer $observer): void
    {
        $order = $observer->getOrder();

        if (!$this->configuration->isEnabled($order->getStoreId())) {
            return;
        }

        $order->setData('ga_client_id', $this->sessionProvider->getClientId());
        $order->setData('ga_session_id', $this->sessionProvider->getSessionId($order->getStoreId()));
    }
}
