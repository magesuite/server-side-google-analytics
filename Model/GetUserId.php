<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model;

class GetUserId
{
    /**
     * @var \Magento\Framework\Stdlib\CookieManagerInterface
     */
    protected $cookieManager;

    public function __construct(\Magento\Framework\Stdlib\CookieManagerInterface $cookieManager)
    {
        $this->cookieManager = $cookieManager;
    }

    public function execute(): string
    {
        return $this->getUserIdFromCookie() ?? $this->generateUserId();
    }

    protected function getUserIdFromCookie(): ?string
    {
        $gaCookie = explode('.', (string)$this->cookieManager->getCookie('_ga'));

        if (empty($gaCookie) || count($gaCookie) < 4) {
            return null;
        }

        list($cookieVersion, $cookieDomainComponents, $cookieUserId, $cookieTimestamp) = $gaCookie;

        if (!$cookieUserId || !$cookieTimestamp) {
            return null;
        }

        if ($cookieVersion != 'GA' . \MageSuite\ServerSideGoogleAnalytics\Model\Client::PROTOCOL_VERSION) {
            return null;
        }

        return implode('.', [$cookieUserId, $cookieTimestamp]);
    }

    protected function generateUserId(): string
    {
        $userId = random_int(100000000, 1000000000);
        $timestamp = time();

        return implode('.', [$userId, $timestamp]);
    }
}
