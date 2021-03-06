<?php

namespace Beecom\Core\Block\Adminhtml\Form\Field;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Shipping\Model\Config;
/**
 * HTML select element block with customer groups options
 */
class ShippingMethods extends \Magento\Framework\View\Element\Html\Select
{
    /**
     * @var Config
     */
    protected $_deliveryModelConfig;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        Config $deliveryModelConfig,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->_deliveryModelConfig = $deliveryModelConfig;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @param bool $quoteLikeCodes
     * @return array
     */
    public function getDeliveryMethods($quoteLikeCodes = false)
    {
        $deliveryMethods = $this->_deliveryModelConfig->getAllCarriers();
        $deliveryMethodsArray = array();
        foreach ($deliveryMethods as $shippingCode => $shippingModel) {
            $shippingTitle = (is_string($shippingModel)) ? $shippingModel : $this->_scopeConfig->getValue('carriers/'.$shippingCode.'/title');
            $shippingCode = ($quoteLikeCodes) ? sprintf("%s_%s", $shippingCode, $shippingCode) : $shippingCode;
            $deliveryMethodsArray[$shippingCode] = $shippingTitle;
        }
        return $deliveryMethodsArray;
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
     * Render block HTML
     *
     * @return string
     */
    public function _toHtml()
    {
        if (!$this->getOptions()) {
            foreach ($this->getDeliveryMethods() as $groupId => $groupLabel) {
                $formattedGroupLabel = sprintf("%s [%s]", $groupLabel, $groupId);
                $this->addOption($groupId, addslashes($formattedGroupLabel));
            }
        }
        return parent::_toHtml();
    }
}
