<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Observer;

class SaveUserIdInOrder implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \MageSuite\ServerSideGoogleAnalytics\Model\GetUserId
     */
    protected $getUserId;

    /**
     * @var \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration
     */
    protected $configuration;

    public function __construct(
        \MageSuite\ServerSideGoogleAnalytics\Model\GetUserId $getUserId,
        \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration
    ) {
        $this->getUserId = $getUserId;
        $this->configuration = $configuration;
    }

    public function execute(\Magento\Framework\Event\Observer $observer): void
    {
        $order = $observer->getOrder();

        if (!$this->configuration->isEnabled($order->getStoreId())) {
            return;
        }

        $userId = $this->getUserId->execute();
        $order->setData('ga_user_id', $userId);
    }
}
