<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Plugin\GoogleAnalytics\Block\Ga;

class DisableOrderTracking
{
    protected \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration;

    public function __construct(\MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function aroundGetOrdersTrackingCode(
        \Magento\GoogleAnalytics\Block\Ga $subject,
        callable $proceed
    ) {
        if ($this->configuration->isEnabled()) {
            return;
        }

        return $proceed();
    }
}
