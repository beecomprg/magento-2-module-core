<?php

namespace Beecom\Core\Model;

use Magento\Framework\Locale\Bundle\DataBundle;
use Magento\Payment\Model\Method\AbstractMethod;
use Magento\Store\Model\ScopeInterface;
use Magento\Payment\Model\Config as BaseModel;

/**
 * Payment configuration model
 *
 * Used for retrieving configuration data by payment models
 *
 * @api
 * @since 100.0.2
 */
class Config extends BaseModel
{
    /**
     * Retrieve active system payments
     *
     * @return array
     * @api
     */
    public function getActiveMethods()
    {
        $methods = [];
        foreach ($this->_scopeConfig->getValue('payment', ScopeInterface::SCOPE_STORE, null) as $code => $data) {
            if (isset($data['active'], $data['model']) && (bool)$data['active']) {
                /** @var MethodInterface $methodModel Actually it's wrong interface */
                $methodModel = $this->_paymentMethodFactory->create($data['model']);
                $methodModel->setStore(null);
                if ($methodModel->getConfigData('active', null)) {
                    $methods[$code] = $methodModel;
                }
            }
        }
        return $methods;
    }

    public function getAllMethods()
    {
        $methods = [];
        foreach ($this->_scopeConfig->getValue('payment', ScopeInterface::SCOPE_STORE, null) as $code => $data) {
            /** @var MethodInterface $methodModel Actually it's wrong interface */
            if (isset($data['active'], $data['model'])) {
                $methodModel = $this->_paymentMethodFactory->create($data['model']);
                $methodModel->setStore(null);
                $methods[$code] = $methodModel;
            }
        }
        return $methods;
    }
}
