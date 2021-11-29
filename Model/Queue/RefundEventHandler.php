<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model\Queue;

class RefundEventHandler implements \MageSuite\Queue\Api\Queue\HandlerInterface
{
    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var \MageSuite\ServerSideGoogleAnalytics\Model\Event\Refund
     */
    protected $refundEvent;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \MageSuite\ServerSideGoogleAnalytics\Model\Event\Refund $refundEvent,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->orderRepository = $orderRepository;
        $this->refundEvent = $refundEvent;
        $this->logger = $logger;
    }

    public function execute($orderId): void
    {
        try {
            $order = $this->orderRepository->get($orderId);
            $this->refundEvent->execute($order);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}
