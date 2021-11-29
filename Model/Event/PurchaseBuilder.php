<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model\Event;

class PurchaseBuilder extends AbstractBuilder
{
    /**
     * @see https://developers.google.com/analytics/devguides/collection/protocol/v1/
     * @return \Magento\Framework\DataObject
     */
    public function create(): \Magento\Framework\DataObject
    {
        $eventData = $this->getEventData();
        $order = $this->getOrder();
        $eventData->setData('uip', (string)$order->getRemoteIp());
        $eventData->setData('dp', '/checkout/onepage/success/');

        return $eventData;
    }

    protected function getProductAction()
    {
        return 'purchase';
    }
}
