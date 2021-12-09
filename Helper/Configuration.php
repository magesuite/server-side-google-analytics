<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Helper;

class Configuration extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_GENERAL_IS_ENABLED = 'server_side_google_analytics/general/enabled';
    const XML_PATH_GENERAL_ACCOUNT_NUMBER = 'server_side_google_analytics/general/account_number';
    const XML_PATH_GENERAL_SANDBOX_MODE = 'server_side_google_analytics/general/sandbox_mode';
    const XML_PATH_GENERAL_DEBUG_MODE = 'server_side_google_analytics/general/debug_mode';
    const XML_PATH_PRODUCT_BRAND_ATTRIBUTE = 'server_side_google_analytics/product/brand_attribute';
    const XML_PATH_PRODUCT_CUSTOM_DIMENSION = 'server_side_google_analytics/product/custom_dimension';

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    protected $serializer;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Serialize\SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
        parent::__construct($context);
    }

    public function isEnabled($storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_GENERAL_IS_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getAccountNumber($storeId = null): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_GENERAL_ACCOUNT_NUMBER,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
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

    public function getBrandAttribute(): string
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_PRODUCT_BRAND_ATTRIBUTE);
    }

    public function getProductCustomDimension($storeId = null): ?array
    {
        $value = $this->scopeConfig->getValue(
            self::XML_PATH_PRODUCT_CUSTOM_DIMENSION,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );

        if (empty($value)) {
            return null;
        }

        return (array)$this->serializer->unserialize($value);
    }
}
