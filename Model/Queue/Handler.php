<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model\Queue;

class Handler implements \MageSuite\Queue\Api\Queue\HandlerInterface
{
    protected \Magento\Framework\Serialize\SerializerInterface $serializer;
    protected \MageSuite\ServerSideGoogleAnalytics\Model\Client $apiClient;
    protected \Psr\Log\LoggerInterface $logger;

    public function __construct(
        \Magento\Framework\Serialize\SerializerInterface $serializer,
        \MageSuite\ServerSideGoogleAnalytics\Model\Client $apiClient,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->serializer = $serializer;
        $this->apiClient = $apiClient;
        $this->logger = $logger;
    }

    public function execute($eventData): void
    {
        $eventData = $this->serializer->unserialize($eventData);

        try {
            $this->apiClient->call(
                $eventData[\MageSuite\ServerSideGoogleAnalytics\Model\Queue\Publisher::EVENT_DATA],
                $eventData[\MageSuite\ServerSideGoogleAnalytics\Model\Queue\Publisher::STORE_ID]
            );
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}
