<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model;

class Client
{
    public const PROTOCOL_VERSION = 1;

    protected \Magento\Framework\HTTP\Client\CurlFactory $curlFactory;
    protected \Magento\Framework\Serialize\SerializerInterface $serializer;
    protected \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration;
    protected \Psr\Log\LoggerInterface $logger;

    public function __construct(
        \Magento\Framework\HTTP\Client\CurlFactory $curlFactory,
        \Magento\Framework\Serialize\SerializerInterface $serializer,
        \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->curlFactory = $curlFactory;
        $this->serializer = $serializer;
        $this->configuration = $configuration;
        $this->logger = $logger;
    }

    public function call(array $body, $storeId = null)
    {
        $apiUrl = $this->getApiUrl($storeId);
        $json = $this->serializer->serialize($body);
        $debugData = ['url' => $apiUrl, 'body' => $json];
        $this->debugLog($debugData);
        $curl = $this->curlFactory->create();
        $curl->addHeader('Content-Type', 'application/json');
        $curl->post($apiUrl, $json);
        $debugData = ['http_code' => $curl->getStatus(), 'body' => $curl->getBody()];
        $this->debugLog($debugData);

        if ($curl->getStatus() >= 200 && $curl->getStatus() < 300) {
            return;
        }

        throw new \Magento\Framework\Exception\LocalizedException(
            __('Unable to process a request to Google Analytics!')
        );
    }

    protected function getApiUrl($storeId = null): string
    {
        $apiUrl = 'https://www.google-analytics.com/';

        if ($this->configuration->isSandboxModeEnabled()) {
            $apiUrl .= 'debug/';
        }

        $data = [
            'measurement_id' => $this->configuration->getMeasurementId($storeId),
            'api_secret' => $this->configuration->getApiSecret($storeId)
        ];
        $apiUrl .= 'mp/collect?' . http_build_query($data);

        return $apiUrl;
    }

    protected function debugLog($debugData): void
    {
        if (!$this->configuration->isDebugModeEnabled()) {
            return;
        }

        $this->logger->debug(var_export($debugData, true));
    }
}
