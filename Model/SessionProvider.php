<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model;

class SessionProvider
{
    protected \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager;
    protected \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration;

    public function __construct(
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration
    ) {
        $this->cookieManager = $cookieManager;
        $this->configuration = $configuration;
    }

    public function getClientId(): ?string
    {
        $cookie = explode('.', (string)$this->cookieManager->getCookie('_ga'));

        if (empty($cookie) || count($cookie) < 4) {
            return null;
        }

        list($cookieVersion, $cookieDomainComponents, $cookieUserId, $cookieTimestamp) = $cookie;

        if (!$cookieUserId || !$cookieTimestamp) {
            return null;
        }

        if ($cookieVersion != 'GA' . \MageSuite\ServerSideGoogleAnalytics\Model\Client::PROTOCOL_VERSION) {
            return null;
        }

        return implode('.', [$cookieUserId, $cookieTimestamp]);
    }

    public function getSessionId($storeId = null): ?string
    {
        $measurementId = $this->configuration->getMeasurementId($storeId);
        $measurementId = str_replace('G-', '', $measurementId);
        $cookie = explode('.', (string)$this->cookieManager->getCookie('_ga_' . $measurementId));

        if (empty($cookie) || count($cookie) < 9) {
            return null;
        }

        list($googleStream, $domainLevel, $sessionId) = $cookie;

        return $sessionId;
    }
}
