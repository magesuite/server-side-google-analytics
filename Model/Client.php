<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model;

class Client
{
    const PROTOCOL_VERSION = 1;

    /**
     * @var \Magento\Framework\HTTP\Client\CurlFactory
     */
    protected $curlFactory;

    /**
     * @var \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(
        \Magento\Framework\HTTP\Client\CurlFactory $curlFactory,
        \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->curlFactory = $curlFactory;
        $this->configuration = $configuration;
        $this->logger = $logger;
    }

    public function call(array $params, $storeId = null)
    {
        $params['v'] = self::PROTOCOL_VERSION;
        $params['tid'] = $this->configuration->getAccountNumber($storeId);
        $apiUrl = $this->getApiUrl();
        $debugData = ['url' => $apiUrl, 'params' => http_build_query($params)];
        $this->debugLog($debugData);
        $curl = $this->curlFactory->create();
        $curl->post($apiUrl, $params);
        $curl->getStatus();
        $debugData = ['http_code' => $curl->getStatus()];
        $this->debugLog($debugData);

        if ($curl->getStatus() !== 200) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Unable to process a request to Google Anayltics!')
            );
        }
    }

    protected function getApiUrl(): string
    {
        if ($this->configuration->isSandboxModeEnabled()) {
            return 'https://www.google-analytics.com/debug/collect';
        }

        return 'https://www.google-analytics.com/collect';
    }

    protected function debugLog($debugData): void
    {
        if (!$this->configuration->isDebugModeEnabled()) {
            return;
        }

        $this->logger->debug(var_export($debugData, true));
    }
}
