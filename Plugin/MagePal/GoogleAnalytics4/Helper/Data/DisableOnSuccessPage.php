<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Plugin\MagePal\GoogleAnalytics4\Helper\Data;

class DisableOnSuccessPage
{
    protected \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration;

    protected \Magento\Framework\App\Request\Http $request;

    public function __construct(
        \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration,
        \Magento\Framework\App\Request\Http $request
    ) {
        $this->configuration = $configuration;
        $this->request = $request;
    }

    public function afterIsEnabled(
        \MagePal\GoogleAnalytics4\Helper\Data $subject,
        $result,
        $storeId = null
    ) {
        if ($this->configuration->isEnabled($storeId)
            && $this->request->getFullActionName() === 'checkout_onepage_success') {
            return false;
        }

        return $result;
    }
}
