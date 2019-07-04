<?php

namespace Beecom\Core\Block\Adminhtml\Form\Field;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Beecom\Core\Model\Config;
use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;

/**
 * HTML select element block with customer groups options
 */
class PaymentMethods extends Select
{
    /**
     * @var Config
     */
    protected $_paymentModelConfig;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    public function __construct(
        Context $context,
        Config $paymentModelConfig,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->_paymentModelConfig = $paymentModelConfig;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Retrieve allowed customer groups
     *
     * @param int $groupId return name by customer group id
     * @return array|string
     */
    protected function _getPaymentMethods()
    {
        $paymentMethods = $this->_paymentModelConfig->getAllMethods();
        $paymentMethodsArray = array();
        foreach ($paymentMethods as $paymentCode => $paymentModel) {
            $methodName = $this->_scopeConfig->getValue('payment/'.$paymentCode.'/title');
            $paymentTitle = sprintf('%s [%s]', $methodName, $paymentCode);
            $paymentMethodsArray[$paymentCode] = $paymentTitle;
        }
        asort($paymentMethodsArray, SORT_STRING);
        return $paymentMethodsArray;
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
            foreach ($this->_getPaymentMethods() as $groupId => $groupLabel) {
                $this->addOption($groupId, addslashes($groupLabel));
            }
        }
        return parent::_toHtml();
    }
}
