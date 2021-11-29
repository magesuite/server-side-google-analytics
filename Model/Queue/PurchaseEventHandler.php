<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model\Queue;

class PurchaseEventHandler implements \MageSuite\Queue\Api\Queue\HandlerInterface
{
    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var \MageSuite\ServerSideGoogleAnalytics\Model\Event\Purchase
     */
    protected $purchaseEvent;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \MageSuite\ServerSideGoogleAnalytics\Model\Event\Purchase $purchaseEvent,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->orderRepository = $orderRepository;
        $this->purchaseEvent = $purchaseEvent;
        $this->logger = $logger;
    }

    public function execute($orderId): void
    {
        try {
            $order = $this->orderRepository->get($orderId);
            $this->purchaseEvent->execute($order);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}
