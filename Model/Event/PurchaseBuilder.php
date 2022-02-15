<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model\Event;

class PurchaseBuilder extends AbstractBuilder
{
    protected \MageSuite\ServerSideGoogleAnalytics\Model\ResourceModel\GetNumberOfOrders $getNumberOfOrders;
    public function __construct(
        \MageSuite\ServerSideGoogleAnalytics\Model\ProductDataProviderInterface $productDataProvider,
        \MageSuite\ServerSideGoogleAnalytics\Helper\Configuration $configuration,
        \MageSuite\ServerSideGoogleAnalytics\Model\ResourceModel\GetNumberOfOrders $getNumberOfOrders
    ) {
        parent::__construct($productDataProvider, $configuration);
        $this->getNumberOfOrders = $getNumberOfOrders;
    }

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
        $numberOfOrders = $this->getNumberOfOrders->execute(
            $order->getCustomerEmail(),
            (int)$order->getId()
        );
        $eventData->setData('cd1', $numberOfOrders);

        return $eventData;
    }

    protected function getProductAction(): string
    {
        return 'purchase';
    }
}
