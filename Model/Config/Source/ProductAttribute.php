<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model\Config\Source;

class ProductAttribute implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory
     */
    protected $collectionFactory;

    public function __construct(\Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        if (!$this->options) {
            $collection = $this->collectionFactory
                ->create()
                ->setOrder('frontend_label', \Magento\Framework\Data\Collection::SORT_ORDER_ASC);
            $options = [];

            foreach ($collection as $attribute) {
                $options[] = [
                    'label' => $attribute->getFrontendLabel(),
                    'value' => $attribute->getAttributeCode()
                ];
            }

            $this->options = $options;
        }

        return $this->options;
    }
}
