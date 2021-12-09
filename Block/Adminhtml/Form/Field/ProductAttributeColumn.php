<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Block\Adminhtml\Form\Field;

class ProductAttributeColumn extends \Magento\Framework\View\Element\Html\Select
{
    /**
     * @var \MageSuite\ServerSideGoogleAnalytics\Model\Config\Source\ProductAttribute
     */
    protected $productAttributeSource;

    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \MageSuite\ServerSideGoogleAnalytics\Model\Config\Source\ProductAttribute $productAttributeSource,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->productAttributeSource = $productAttributeSource;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * @param $value
     * @return $this
     */
    public function setInputId($value)
    {
        return $this->setId($value);
    }

    /**
     * @return string
     */
    public function _toHtml(): string
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->getSourceOptions());
        }
        return parent::_toHtml();
    }

    /**
     * @return array
     */
    protected function getSourceOptions(): array
    {
        return $this->productAttributeSource->toOptionArray();
    }
}
