<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model\Queue;

class Publisher
{
    public const EVENT_DATA = 'event_data';
    public const STORE_ID = 'store_id';
    protected $handlerClass = \MageSuite\ServerSideGoogleAnalytics\Model\Queue\Handler::class;

    protected \MageSuite\Queue\Service\Publisher $queuePublisher;
    protected \Magento\Framework\Serialize\SerializerInterface $serializer;

    public function __construct(
        \MageSuite\Queue\Service\Publisher $queuePublisher,
        \Magento\Framework\Serialize\SerializerInterface $serializer
    ) {
        $this->queuePublisher = $queuePublisher;
        $this->serializer = $serializer;
    }

    public function publish(array $eventData, int $storeId): void
    {
        $this->queuePublisher->publish(
            $this->handlerClass,
            $this->serializer->serialize([
                self::EVENT_DATA => $eventData,
                self::STORE_ID => $storeId
            ])
        );
    }
}
