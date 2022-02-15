<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model\Event;

class RefundBuilder extends AbstractBuilder
{
    /**
     * @see https://developers.google.com/analytics/devguides/collection/protocol/v1/
     * @return \Magento\Framework\DataObject
     */
    public function create(): \Magento\Framework\DataObject
    {
        return $this->getEventData();
    }

    protected function getProductAction(): string
    {
        return 'refund';
    }
}
