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
     * Retrieve allowed customer groups
     *
     * @param int $groupId return name by customer group id
     * @return array|string
     */
    public function getDeliveryMethods()
    {
        $deliveryMethods = $this->_deliveryModelConfig->getAllCarriers();
        $deliveryMethodsArray = array();
        foreach ($deliveryMethods as $shippigCode => $shippingModel) {
            $shippingTitle = (is_string($shippingModel)) ? $shippingModel : $this->_scopeConfig->getValue('carriers/'.$shippigCode.'/title');
            $deliveryMethodsArray[$shippigCode] = sprintf("%s [%s]", $shippingTitle, $shippigCode);
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
                $this->addOption($groupId, addslashes($groupLabel));
            }
        }
        return parent::_toHtml();
    }
}
