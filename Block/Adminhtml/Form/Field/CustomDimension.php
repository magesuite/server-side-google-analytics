<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Block\Adminhtml\Form\Field;

class CustomDimension extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    /**
     * @var \MageSuite\ServerSideGoogleAnalytics\Block\Adminhtml\Form\Field\ProductAttributeColumn
     */
    protected $productAttributeRenderer;

    protected function _prepareToRender()
    {
        $this->addColumn('dimension_index', [
            'label' => __('Dimension Index'),
            'class' => 'required-entry validate-digits-range digits-range-1-200'
        ]);
        $this->addColumn('product_attribute', [
            'label' => __('Product Attribute'),
            'renderer' => $this->getProductAttributeRenderer()
        ]);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    /**
     * @param \Magento\Framework\DataObject $row
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareArrayRow(\Magento\Framework\DataObject $row): void
    {
        $options = [];
        $productAttribute = $row->getProductAttribute();

        if ($productAttribute !== null) {
            $options['option_' . $this->getProductAttributeRenderer()->calcOptionHash($productAttribute)] = 'selected="selected"';
        }

        $row->setData('option_extra_attrs', $options);
    }

    /**
     * @return \MageSuite\ServerSideGoogleAnalytics\Block\Adminhtml\Form\Field\ProductAttributeColumn
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getProductAttributeRenderer()
    {
        if (!$this->productAttributeRenderer) {
            $this->productAttributeRenderer = $this->getLayout()->createBlock(
                \MageSuite\ServerSideGoogleAnalytics\Block\Adminhtml\Form\Field\ProductAttributeColumn::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }

        return $this->productAttributeRenderer;
    }
}
