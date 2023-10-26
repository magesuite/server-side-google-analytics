<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Helper;

class Configuration
{
    const XML_PATH_GENERAL_IS_ENABLED = 'server_side_google_analytics/general/enabled';
    const XML_PATH_GENERAL_MEASUREMENT_ID = 'server_side_google_analytics/general/measurement_id';
    const XML_PATH_GENERAL_API_SECRET = 'server_side_google_analytics/general/api_secret';
    const XML_PATH_GENERAL_SANDBOX_MODE = 'server_side_google_analytics/general/sandbox_mode';
    const XML_PATH_GENERAL_DEBUG_MODE = 'server_side_google_analytics/general/debug_mode';

    protected \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig;
    protected \Magento\Framework\Encryption\EncryptorInterface $encryptor;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->encryptor = $encryptor;
    }

    public function isEnabled($storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_GENERAL_IS_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getMeasurementId($storeId = null): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_GENERAL_MEASUREMENT_ID,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getApiSecret($storeId = null): ?string
    {
        $value = $this->scopeConfig->getValue(self::XML_PATH_GENERAL_API_SECRET);

        if ($value === null) {
            return null;
        }

        return $this->encryptor->decrypt($value);
    }

    public function isSandboxModeEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_GENERAL_SANDBOX_MODE);
    }

    public function isDebugModeEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_GENERAL_DEBUG_MODE);
    }

    public function excludeTaxFromCalculation($storeId = null): bool
    {
        return $this->scopeConfig->getValue(
            \Magento\Tax\Model\Config::CONFIG_XML_PATH_PRICE_DISPLAY_TYPE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        ) == \Magento\Tax\Model\Config::DISPLAY_TYPE_EXCLUDING_TAX;
    }
}
